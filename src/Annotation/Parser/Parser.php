<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

use Igni\OpenApi\Annotation\Parser\MetaData\Annotation;
use Igni\OpenApi\Annotation\Parser\MetaData\Enum;
use Igni\OpenApi\Annotation\Parser\MetaData\Required;
use Igni\OpenApi\Annotation\Parser\MetaData\Target;
use Igni\OpenApi\Exception\ParserException;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

class Parser
{
    private const VALUE_TOKENS = [
        Token::T_NULL,
        Token::T_STRING,
        Token::T_FLOAT,
        Token::T_INTEGER,
        Token::T_TRUE,
        Token::T_FALSE
    ];

    private $ignoreNotImported = false;
    private $fileImports;
    private $phpParser;

    private $ignored = [];

    private $namespaces = [

    ];

    private const DOCBLOCK_TAGS = [
        'api',
        'author',
        'category',
        'copyright',
        'deprecated',
        'example',
        'filesource',
        'global',
        'ignore',
        'internal',
        'license',
        'link',
        'method',
        'package',
        'param',
        'property',
        'property-read',
        'property-write',
        'return',
        'see',
        'since',
        'source',
        'subpackage',
        'throws',
        'todo',
        'uses',
        'used-by',
        'var',
        'version',
    ];

    private const BUILT_IN = [
        'Annotation' => Annotation::class,
        'Enum' => Enum::class,
        'Required' => Required::class,
        'Target' => Target::class,
    ];

    private $metaData = [
        Annotation::class => [
            'target' => [Target::TARGET_CLASS],
            'constructor' => false,
            'validate' => false,
            'properties' => [],
        ],
        Required::class => [
            'target' => [Target::TARGET_PROPERTY],
            'constructor' => false,
            'validate' => false,
            'properties' => [],
        ],
        Target::class => [
            'constructor' => true,
            'validate' => false,
            'properties' => [],
        ],
        Enum::class => [
            'constructor' => true,
            'validate' => false,
            'properties' => [],
        ]
    ];

    public function __construct()
    {
        $this->phpParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    public function addIgnore(string $name) : void
    {
        $this->ignored[] = $name;
    }

    public function ignoreNotImportedAnnotations(bool $ignore = true) : void
    {
        $this->ignoreNotImported = $ignore;
    }

    /**
     * @param DocBlock $docBlock
     * @return array
     * @throws
     */
    public function parse(DocBlock $docBlock): array
    {
        $tokenizer = new Tokenizer((string) $docBlock);
        $tokenizer->tokenize();

        // Lets search for fist annotation occurrence in docblock
        if (!$tokenizer->seek(Token::T_AT)) {
            // No annotations in docblock.
            return [];
        }
        $annotations = [];

        while ($tokenizer->valid() && $tokenizer->seek(Token::T_AT)) {

            // Annotation must be preceded by an asterisk token, otherwise it should be ignored
            if ($tokenizer->key() > 1 && $tokenizer->at($tokenizer->key() - 1)->getType() !== Token::T_ASTERISK) {
                $tokenizer->next();
                continue;
            }

            $annotations[] = $this->parseAnnotation($tokenizer, $docBlock);
        }

        return $annotations;
    }

    private function parseAnnotation(Tokenizer $tokenizer, DocBlock $context, $nested = false)
    {
        // Skip @
        $tokenizer->next();
        $name = $this->parseIdentifier($tokenizer);
        $arguments = $this->parseArguments($tokenizer, $context);

        return $name;
    }

    private function parseIdentifier(Tokenizer $tokenizer)
    {
        $identifier = '';

        while ($tokenizer->valid() && in_array($tokenizer->current()->getType(), [Token::T_IDENTIFIER, Token::T_NAMESPACE_SEPARATOR], true)) {
            $identifier .= $tokenizer->current()->getValue();
            $tokenizer->next();
        }

        return $identifier;
    }

    private function parseArguments(Tokenizer $tokenizer, DocBlock $context) : array
    {
        $arguments = [];

        if ($tokenizer->current()->getType() !== Token::T_OPEN_PARENTHESIS) {
            return $arguments;
        }

        $this->expect(Token::T_OPEN_PARENTHESIS, $tokenizer, $context);
        $tokenizer->next();

        $this->parseArgument($tokenizer, $context, $arguments);

        while ($tokenizer->current()->getType() === Token::T_COMMA) {
            $tokenizer->next();
            $this->parseArgument($tokenizer, $context, $arguments);
        }

        $this->expect(Token::T_CLOSE_PARENTHESIS, $tokenizer, $context);

        return $arguments;
    }

    private function parseArgument(Tokenizer $tokenizer, DocBlock $context, array &$arguments) : void
    {
        // key / value pair
        if ($tokenizer->at($tokenizer->key() + 1)->getType() === Token::T_EQUALS) {
            $key = $tokenizer->current()->getValue();
            $this->skip(2, $tokenizer);
            $arguments[$key] = $this->parseValue($tokenizer, $context);
            return;
        }

        // Just value
        $arguments[] = $this->parseValue($tokenizer, $context);
    }

    private function parseValue(Tokenizer $tokenizer, DocBlock $context)
    {
        $token = $tokenizer->current();

        // Resolve annotation
        if ($token->getType() === Token::T_AT) {
            return $this->parseAnnotation($tokenizer, $context, true);
        }

        // Resolve primitives
        if (in_array($token->getType(), self::VALUE_TOKENS, true)) {
            $value = $token->getValue();
            $tokenizer->next();
            return $value;
        }

        // Identifier
        $this->expect(Token::T_IDENTIFIER, $tokenizer, $context);
        $identifier = $this->parseIdentifier($tokenizer);
        $token = $tokenizer->current();

        // Resolve ::class
        if ($token->getType() === Token::T_COLON) {
            if (strtolower($this->catch(2, $tokenizer)) !== ':class') {
                $tokenizer->next();
                return $this->resolveFullyQualifiedClassName($identifier, $context);
            }
            throw ParserException::forUnexpectedToken($tokenizer->current(), $context);
        }

        // Resolve constant
        if ($token->getType() === Token::T_COMMA || $token->getType() === Token::T_CLOSE_PARENTHESIS) {
            if (defined($identifier)) {
                $tokenizer->next();
                return constant($identifier);
            }
            throw ParserException::forUnexpectedToken($token, $context);
        }
    }

    private function resolveFullyQualifiedClassName(string $identifier, DocBlock $context) : ?string
    {
        if (class_exists($identifier)) {
            return $identifier;
        }

        $identifier = explode('\\', $identifier);
        $imports = $context->getImports();
        if (isset($imports[$identifier[0]])) {
            $identifier = $imports[$identifier[0]] . '\\' . implode('\\', array_slice($identifier, 1));
        }

        if (class_exists($identifier)) {
            return $identifier;
        }
    }

    private function expect(int $expectedType, Tokenizer $tokenizer, DocBlock $context) : void
    {
        if ($expectedType !== $tokenizer->current()->getType()) {
            throw ParserException::forUnexpectedToken($tokenizer->current(), $context);
        }
    }

    private function skip(int $length, Tokenizer $tokenizer) : void
    {
        for (;$length > 0; $length--) {
            $tokenizer->next();
            if (!$tokenizer->valid()) {
                return;
            }
        }
    }

    private function catch(int $length, Tokenizer $tokenizer) : string
    {
        $value = '';
        for (;$length > 0; $length--) {
            $tokenizer->next();
            if (!$tokenizer->valid()) {
                return $value;
            }

            $value .= $tokenizer->current()->getValue();
        }

        return $value;
    }
}
