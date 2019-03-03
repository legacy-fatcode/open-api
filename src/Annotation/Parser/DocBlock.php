<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionClass;
use ReflectionProperty;

class DocBlock
{
    /**
     * @var string[]
     */
    private static $fileImports = [];

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var string
     */
    private $doc;

    /**
     * @var string[]
     */
    private $imports = [];

    public function __construct(string $doc, string $symbol = '')
    {
        $this->doc = $doc;
        $this->symbol = $symbol;
    }

    public function getSymbol() : string
    {
        return $this->symbol;
    }

    public function addNamespace(string $name, string $alias = '') : void
    {
        $this->imports[$alias] = $name;
    }

    public function getImports() : array
    {
        return $this->imports;
    }

    public function __toString() : string
    {
        return $this->doc;
    }

    public static function fromReflectionClass(ReflectionClass $class) : self
    {
        $instance = new self(
            $class->getDocComment(),
            $class->getName()
        );

        $imports = self::getFileImports($class->getFileName());

        foreach ($imports as $alias => $namespace) {
            $instance->addNamespace($namespace, $alias);
        }

        return $instance;
    }

    public static function fromReflectionMethod(ReflectionMethod $method) : self
    {
        $instance = new self(
            $method->getDocComment(),
            "{$method->getDeclaringClass()->getName()}::{$method->getName()}()"
        );

        $imports = self::getFileImports($method->getFileName());

        foreach ($imports as $alias => $namespace) {
            $instance->addNamespace($namespace, $alias);
        }

        return $instance;
    }

    public static function fromReflectionProperty(ReflectionProperty $property) : self
    {
        $instance = new self(
            $property->getDocComment(),
            "{$property->getDeclaringClass()->getName()}::\${$property->getName()}"
        );

        $imports = self::getFileImports($property->getDeclaringClass()->getFileName());

        foreach ($imports as $alias => $namespace) {
            $instance->addNamespace($namespace, $alias);
        }

        return $instance;
    }

    public static function fromReflectionFunction(ReflectionFunction $function) : self
    {
        $instance = new self(
            $function->getDocComment(),
            "{$function->getName()}()"
        );

        $imports = self::getFileImports($function->getFileName());

        foreach ($imports as $alias => $namespace) {
            $instance->addNamespace($namespace, $alias);
        }

        return $instance;
    }

    private static function getFileImports($filename) : array
    {
        static $phpParser;
        if ($phpParser === null) {
            $phpParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        }

        if (isset(self::$fileImports[$filename])) {
            return self::$fileImports[$filename];
        }

        if (empty($filename) || !is_file($filename) || !is_readable($filename)) {
            return self::$fileImports[$filename] = [];
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
            $ast = $phpParser->parse(file_get_contents($filename));
            $traverser->traverse($ast);
        } catch (Error $exception) {
            return self::$fileImports[$filename] = [];
        }

        return self::$fileImports[$filename] = $useStatements;
    }
}
