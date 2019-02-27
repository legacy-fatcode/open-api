<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;

AnnotationRegistry::registerLoader('class_exists');

/**
 * Class AnnotationReader
 *
 * @see \Doctrine\Common\Annotations\AnnotationReader
 */
class AnnotationReader
{
    private $parser;

    private $phpParser;

    private $imports = [];

    private $fileImports = [];

    public function __construct()
    {
        $this->parser = new DocParser();
        $this->parser->setIgnoreNotImportedAnnotations(true);
        $this->phpParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    public function readFromClass(ReflectionClass $class) : array
    {
        $this->parser->setTarget(Target::TARGET_CLASS);
        $this->parser->setImports($this->getClassImports($class));

        return $this->parser->parse($class->getDocComment(), 'class ' . $class->getName());
    }

    public function readFromProperty(ReflectionProperty $property) : array
    {
        $class = $property->getDeclaringClass();
        $context = 'property ' . $class->getName() . "::\$" . $property->getName();
        $this->parser->setTarget(Target::TARGET_PROPERTY);
        $this->parser->setImports($this->getPropertyImports($property));

        return $this->parser->parse($property->getDocComment(), $context);
    }

    public function readFromMethod(ReflectionMethod $method) : array
    {
        $class = $method->getDeclaringClass();
        $context = 'method ' . $class->getName() . '::' . $method->getName() . '()';

        $this->parser->setTarget(Target::TARGET_METHOD);
        $this->parser->setImports($this->getMethodImports($method));

        return $this->parser->parse($method->getDocComment(), $context);
    }

    public function readFromComment(string $comment, string $filename = null) : array
    {
        $context = "comment in {$filename}";
        $this->parser->setImports($this->getFileImports($filename));

        return $this->parser->parse($comment, $context);
    }

    public function readFromFunction(ReflectionFunction $function) : array
    {
        $context = 'function ' . $function->getName() . '()';
        $this->parser->setTarget(Target::TARGET_METHOD);
        $this->parser->setImports($this->getFunctionImports($function));

        return $this->parser->parse($function->getDocComment(), $context);
    }

    private function getFunctionImports(ReflectionFunction $function) : array
    {
        return $this->getFileImports($function->getFileName());
    }

    private function getClassImports(ReflectionClass $class) : array
    {
        $name = $class->getName();
        if (isset($this->imports[$name])) {
            return $this->imports[$name];
        }

        $this->imports[$name] = array_merge(
            $this->getFileImports($class->getFileName())
        );

        return $this->imports[$name];
    }

    private function getMethodImports(ReflectionMethod $method)
    {
        $class = $method->getDeclaringClass();
        $classImports = $this->getClassImports($class);
        if (!method_exists($class, 'getTraits')) {
            return $classImports;
        }

        $traitImports = [];

        foreach ($class->getTraits() as $trait) {
            if ($trait->hasMethod($method->getName()) && $trait->getFileName() === $method->getFileName()) {
                $traitImports = array_merge($traitImports, $this->getFileImports($trait->getFileName()));
            }
        }

        return array_merge($classImports, $traitImports);
    }

    private function getPropertyImports(ReflectionProperty $property)
    {
        $class = $property->getDeclaringClass();
        $classImports = $this->getClassImports($class);
        if (!method_exists($class, 'getTraits')) {
            return $classImports;
        }

        $traitImports = [];

        foreach ($class->getTraits() as $trait) {
            if ($trait->hasProperty($property->getName())) {
                $traitImports = array_merge($traitImports, $this->getFileImports($trait->getFileName()));
            }
        }

        return array_merge($classImports, $traitImports);
    }

    /**
     * @param $filename
     * @return array
     * @todo: Optimize this to token_get_all function.
     */
    private function getFileImports($filename) : array
    {
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
