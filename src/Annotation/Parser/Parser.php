<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

use Igni\OpenApi\Annotation\Parser\MetaData\Annotation;
use Igni\OpenApi\Annotation\Parser\MetaData\Enum;
use Igni\OpenApi\Annotation\Parser\MetaData\Required;
use Igni\OpenApi\Annotation\Parser\MetaData\Target;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

class Parser
{
    private $fileImports;
    private $phpParser;

    private $namespaces = [

    ];

    private $annotations = [
        'Annotation' => Annotation::class,
        'Enum' => Enum::class,
        'Required' => Required::class,
        'Target' => Target::class,
    ];

    private $metaData = [
        Annotation::class => [
            'target' => [Target::TARGET_CLASS],
            'constructor' => false,
            'properties' => [],
        ],
        Required::class => [
            'target' => [Target::TARGET_PROPERTY],
            'constructor' => false,
            'properties' => [],
        ],
        Target::class => [
            'constructor' => true,
            'properties' => [],
        ],
        Enum::class => [
            'constructor' => true,
            'properties' => [],
        ]
    ];

    public function __construct()
    {
        $this->phpParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    public function parse(string $docBlock): array
    {
        $tokenizer = new Tokenizer($docBlock);
        $tokenizer->tokenize();

        // Lets search for fist annotation occurrence in docblock
        $token = $tokenizer->seek(Token::T_AT);

        return [];
    }

    private function getFileImports(Context $context) : array
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
