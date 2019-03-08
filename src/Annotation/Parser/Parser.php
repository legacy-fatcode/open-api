<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

use Igni\OpenApi\Annotation\Parser\Annotation\Annotation;
use Igni\OpenApi\Annotation\Parser\Annotation\Enum;
use Igni\OpenApi\Annotation\Parser\Annotation\NoValidate;
use Igni\OpenApi\Annotation\Parser\Annotation\Required;
use Igni\OpenApi\Annotation\Parser\Annotation\Target;
use Igni\OpenApi\Annotation\Parser\MetaData\MetaDataExtractor;
use Igni\OpenApi\Exception\ParserException;
use ReflectionClass;

class Parser
{
    private const PHP_ANNOTATIONS = [
        // PHP Documentator
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

        // PHP Unit
        'codeCoverageIgnore',
        'codeCoverageIgnoreEnd',
        'codeCoverageIgnoreStart',

        //PhpStorm
        'noinspection',

        //PhpCodeSniffer
        'codingStandardsIgnoreStart',
        'codingStandardsIgnoreEnd',

        // PEAR
        'package_version',

    ];

    private const BUILT_IN = [
        'Annotation' => Annotation::class,
        'Enum' => Enum::class,
        'Required' => Required::class,
        'Target' => Target::class,
        'NoValidate' => NoValidate::class,
    ];

    private $ignoreNotImported = false;
    private $ignored = [];
    private $autoloadNamespaces = [];
    private $metaDataExtractor;

    private $metaData = [
        Annotation::class => [
            'is_annotation' => true,
            'validate' => false,
            'has_constructor' => false,
            'properties' => [],
        ],
        Enum::class => [
            'is_annotation' => true,
            'validate' => false,
            'has_constructor' => false,
            'properties' => [],
        ],
        NoValidate::class => [
            'is_annotation' => true,
            'validate' => false,
            'has_constructor' => false,
            'properties' => [],
        ],
        Target::class => [
            'is_annotation' => true,
            'validate' => false,
            'has_constructor' => false,
            'properties' => [],
        ],
        Required::class => [
            'is_annotation' => true,
            'validate' => false,
            'has_constructor' => false,
            'properties' => [],
        ],
    ];

    public function __construct()
    {
        $this->metaDataExtractor = new MetaDataExtractor($this);
    }

    public function registerNamespace(string $namespace, string $alias) : void
    {
        $this->autoloadNamespaces[$alias] = $namespace;
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
     * @param string $docBlock
     * @param Context $context
     * @return array
     * @throws
     */
    public function parse(string $docBlock, Context $context = null): array
    {
        if ($context === null) {
            $context = new Context(Target::TARGET_ALL, self::class . '::' . __METHOD__ . '()');
        }

        $tokenizer = new Tokenizer($docBlock);

        // Lets search for fist annotation occurrence in docblock
        if (!$tokenizer->seek(Token::T_AT)) {
            // No annotations in docblock.
            return [];
        }
        $annotations = [];

        while ($tokenizer->valid() && $tokenizer->seek(Token::T_AT)) {

            // Annotation must be preceded by a new line token, otherwise it should be ignored
            if ($tokenizer->key() > 1 && $tokenizer->at($tokenizer->key() - 1)->getType() !== Token::T_EOL) {
                $tokenizer->next();
                continue;
            }
            // Skip @
            $tokenizer->next();
            $annotation = $this->parseAnnotation($tokenizer, $context);
            if ($annotation === null) {
                continue;
            }
            $annotations[] = $annotation;
        }

        return $annotations;
    }

    private function parseAnnotation(Tokenizer $tokenizer, Context $context, $nested = false)
    {
        $identifier = $tokenizer->current()->getValue();
        $tokenizer->next();
        // Ignore one-line utility annotations
        if (in_array($identifier, self::PHP_ANNOTATIONS, true)) {
            return null;
        }

        $arguments = $this->parseArguments($tokenizer, $context);

        // Other ignored annotations have to be parsed before we ignore them.
        if (in_array($identifier, $this->ignored, true)) {
            return null;
        }

        $annotationClass = $this->resolveFullyQualifiedClassName($identifier, $context);

        if (!class_exists($annotationClass)) {
            if ($this->ignoreNotImported) {
                return null;
            }
            throw ParserException::forUnknownAnnotationClass($identifier, $context);
        }


        $metaData = $this->getMetaData($annotationClass, $context);


        if (!$metaData['has_constructor']) {
            $annotation = new $annotationClass();
            $valueArgs = [];
            foreach ($arguments as $key => $value) {
                if (is_numeric($key)) {
                    $valueArgs[] = $value;
                    continue;
                }
                if (property_exists($annotation, $key)) {
                    $annotation->{$key} = $value;
                }
            }
            if (property_exists($annotation, 'value')) {
                $annotation->value = $valueArgs;
            }
        } else {
            $annotation = new $annotationClass($arguments);
        }

        return $annotation;
    }

    private function parseArguments(Tokenizer $tokenizer, Context $context) : array
    {
        $arguments = [];

        if ($tokenizer->current()->getType() !== Token::T_OPEN_PARENTHESIS) {
            return $arguments;
        }

        $this->expect(Token::T_OPEN_PARENTHESIS, $tokenizer, $context);
        $tokenizer->next();

        $this->parseArgument($tokenizer, $context, $arguments);

        while ($this->match($tokenizer, Token::T_COMMA)) {
            $tokenizer->next();
            $this->parseArgument($tokenizer, $context, $arguments);
        }

        $this->expect(Token::T_CLOSE_PARENTHESIS, $tokenizer, $context);

        return $arguments;
    }


    private function parseArgument(Tokenizer $tokenizer, Context $context, array &$arguments) : void
    {
        $this->ignoreEndOfLine($tokenizer);
        // There was a comma with no value afterwards
        if ($this->match($tokenizer, Token::T_CLOSE_PARENTHESIS)) {
            return;
        }

        // key / value pair
        if ($tokenizer->at($tokenizer->key() + 1)->getType() === Token::T_EQUALS) {
            $key = $tokenizer->current()->getValue();
            $this->skip(2, $tokenizer);
            $arguments[$key] = $this->parseValue($tokenizer, $context);
            return;
        }

        // Just value
        $arguments[] = $this->parseValue($tokenizer, $context);
        $this->ignoreEndOfLine($tokenizer);
    }

    private function parseValue(Tokenizer $tokenizer, Context $context)
    {
        $token = $tokenizer->current();
        $tokenizer->next();

        // Resolve annotation
        if ($token->getType() === Token::T_AT) {
            return $this->parseAnnotation($tokenizer, $context, true);
        }

        // Resolve primitives
        switch ($token->getType()) {
            case Token::T_STRING:
                return $token->getValue();

            case Token::T_INTEGER:
                return (int) $token->getValue();

            case Token::T_FLOAT:
                return (float) $token->getValue();

            case Token::T_NULL:
                return null;

            case Token::T_FALSE:
                return false;

            case Token::T_TRUE:
                return true;
        }

        $constant = $token->getValue();

        // Class constant
        if (strpos($constant, '::') !== false) {
            $constant = explode('::', $constant);

            $class = $this->resolveFullyQualifiedClassName($constant[0], $context);
            if ($constant[1] === 'class') {
                return $class;
            }
            $constant = $class . '::' . $constant[1];
        }

        return constant($constant);
    }

    private function getMetaData(string $annotationClass, Context $context) : array
    {
        if (isset($this->metaData[$annotationClass])) {
            return $this->metaData[$annotationClass];
        }

        $annotationReflection = new ReflectionClass($annotationClass);
        if (strpos($annotationReflection->getDocComment(), '@Annotation') === false) {
            throw ParserException::forUsingNonAnnotationClassAsAnnotation($annotationClass, $context);
        }

        return $this->metaData[$annotationClass] = $this->metaDataExtractor->extract($annotationReflection, $context);
    }

    private function resolveFullyQualifiedClassName(string $identifier, Context $context) : ?string
    {
        if (isset(self::BUILT_IN[$identifier])) {
            return self::BUILT_IN[$identifier];
        }

        if (class_exists($identifier)) {
            return $identifier;
        }

        if (class_exists($context->getNamespace() . '\\' . $identifier)) {
            return $context->getNamespace() . '\\' . $identifier;
        }

        $identifier = explode('\\', $identifier);
        $imports = $context->getImports() + $this->autoloadNamespaces;
        if (isset($imports[$identifier[0]])) {
            $identifier = array_merge(explode('\\', $imports[$identifier[0]]), array_slice($identifier, 1));
        }
        $identifier = implode('\\', $identifier);
        if (class_exists($identifier)) {
            return $identifier;
        }

        return null;
    }

    private function match(Tokenizer $tokenizer, int $type) : bool
    {
        return $tokenizer->current()->getType() === $type;
    }

    private function expect(int $expectedType, Tokenizer $tokenizer, Context $context) : void
    {
        if ($expectedType !== $tokenizer->current()->getType()) {
            throw ParserException::forUnexpectedToken($tokenizer->current(), $context);
        }
    }

    private function ignoreEndOfLine(Tokenizer $tokenizer) : bool
    {
        if ($tokenizer->current()->getType() === Token::T_EOL) {
            $this->skip(1, $tokenizer);
            return true;
        }

        return false;
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
}
