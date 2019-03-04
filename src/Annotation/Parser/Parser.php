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
    private const S_DOCBLOCK = 'doblock';
    private const S_ANNOTATION = 'anno';
    private const S_ANNOTATION_CONSTRUCTOR = 'anno_const';
    private const S_PARAM_NAME = 'param_name';
    private const S_PARAM_VALUE = 'param_value';
    private const S_ARRAY = 'array';

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

        $stateTree = [];
        $currentState = self::S_DOCBLOCK;
        $stateTree[] = $currentState;

        $astTree = [];
        $astNode = null;
        $paramName = null;

        while ($tokenizer->valid()) {
            $token = $tokenizer->current();
            $tokenizer->next();

            switch ($token->getType()) {
                case Token::T_AT:
                    if ($currentState !== self::S_DOCBLOCK) {
                        throw ParserException::forUnexpectedToken($token, $docBlock);
                    }
                    $currentState = self::S_ANNOTATION;
                    $stateTree[] = $currentState;

                    $astNode = [
                        'annotation' => $this->catchAnnotationName($tokenizer, $docBlock),
                        'properties' => [],
                        'parameters' => [],
                    ];
                    $astTree[] = &$astNode;
                    break;
                case Token::T_OPEN_PARENTHESIS:
                    if ($currentState !== self::S_ANNOTATION) {
                        throw ParserException::forUnexpectedToken($token, $docBlock);
                    }
                    $currentState = self::S_ANNOTATION_CONSTRUCTOR;
                    $stateTree[] = $currentState;
                    break;
                case Token::T_CLOSE_PARENTHESIS:
                    if ($currentState === self::S_PARAM_VALUE) {
                        array_pop($stateTree);
                        $currentState = end($stateTree);
                    }
                    if ($currentState !== self::S_ANNOTATION_CONSTRUCTOR) {
                        throw ParserException::forUnexpectedToken($token, $docBlock);
                    }
                    array_pop($stateTree);
                    $currentState = end($stateTree);
                    break;
                case Token::T_OPEN_BRACKET:
                    if ($currentState !== self::S_ANNOTATION_CONSTRUCTOR || $currentState !== self::S_ARRAY) {
                        throw ParserException::forUnexpectedToken($token, $docBlock);
                    }
                    break;
                case Token::T_CLOSE_BRACKET:
                    if ($currentState !== self::S_ARRAY) {
                        throw ParserException::forUnexpectedToken($token, $docBlock);
                    }
                    break;
                case Token::T_IDENTIFIER:
                    if  ($currentState !== self::S_ANNOTATION_CONSTRUCTOR) {
                        throw ParserException::forUnexpectedToken($token, $docBlock);
                    }
                    $identifier = $this->catchConstOrParamName($tokenizer, $docBlock);

                    break;
                // Catch values
                case Token::T_STRING:
                case Token::T_FALSE:
                case Token::T_TRUE:
                case Token::T_INTEGER:
                case Token::T_FLOAT:
                case Token::T_NULL:
                    if ($currentState !== self::S_ANNOTATION_CONSTRUCTOR) {
                        throw ParserException::forUnexpectedToken($token, $docBlock);
                    }
                    $astNode['parameters'][] = $token->getValue();
                    $currentState = self::S_PARAM_VALUE;
                    $stateTree[] = $currentState;
                    break;
                case Token::T_COMMA:
                    if ($currentState !== self::S_PARAM_VALUE) {
                        throw ParserException::forUnexpectedToken($token, $docBlock);
                    }
                    array_pop($stateTree);
                    $currentState = end($stateTree);
                    break;
                case Token::T_EQUALS:
                    break;
                case Token::T_EOL:
                    break;
            }
        }

        return $astTree;
    }

    private function instantiateAnnotation(array &$annotationStack, array &$propertiesStack)
    {
        $annotationName = array_pop($annotationStack);
        $properties = array_pop($propertiesStack);

        if (in_array($annotationName, $this->ignored)) {
            return null;
        }

        if (in_array($annotationName, self::BUILT_IN)) {
            $annotationClass = self::BUILT_IN[$annotationName];
        } elseif (class_exists($annotationName)) {
            $annotationClass = $annotationName;
        } else {

        }

        $metaData = $this->getMetaData($annotationClass);
    }

    private function getMetaData(string $annotationClass) : array
    {
        if (isset($this->metaData[$annotationClass])) {
            return $this->metaData[$annotationClass];
        }

        $this->metaData[$annotationClass] = $this->gatherAnnotationMetaData($annotationClass);
    }

    private function catchAnnotationName(Tokenizer $tokenizer, DocBlock $context) : string
    {
        $name = '';
        while ($tokenizer->valid()) {
            $token = $tokenizer->current();
            switch ($token->getType()) {
                case Token::T_IDENTIFIER:
                case Token::T_NAMESPACE_SEPARATOR:
                    $name .= $token->getValue();
                    break;

                case Token::T_OPEN_PARENTHESIS:
                case Token::T_ASTERISK:
                case Token::T_AT:
                case Token::T_EOL:
                    return $name;

                default:
                    throw ParserException::forUnexpectedToken($token, $context);
                    break;
            }
            $tokenizer->next();
        }

        return $name;
    }

    private function gatherAnnotationMetaData(string $annotation) : array
    {
        return [

        ];
    }

    private function getFileImports(DocBlock $context) : array
    {
        $filename = $context->getFilename();
        if (isset($this->fileImports[$filename])) {
            return $this->fileImports[$filename];
        }

        if (empty($filename) || !is_file($filename) || !is_readable($filename)) {
            return $this->fileImports[$filename] = [];
        }

        $useStatements = [];
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new class($useStatements) extends NodeVisitorAbstract {
            private $useStatements;

            public function __construct(&$useStatements)
            {
                $this->useStatements = &$useStatements;
            }

            public function enterNode(Node $node) {
                if ($node instanceof Node\Stmt\UseUse) {
                    $this->useStatements[strtolower((string) $node->getAlias())] = (string) $node->name;
                }
            }
        });

        try {
            $ast = $this->phpParser->parse(file_get_contents($filename));
            $traverser->traverse($ast);
        } catch (Error $exception) {
            return $this->fileImports[$filename] = [];
        }

        return $this->fileImports[$filename] = $useStatements;
    }
}
