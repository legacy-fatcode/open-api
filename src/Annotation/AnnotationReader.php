<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;
use Doctrine\Common\Annotations\PhpParser;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;

AnnotationRegistry::registerLoader('class_exists');

/**
 * Class AnnotationReader
 * This class is partially rewritten doctrine annotation reader class:
 * @see \Doctrine\Common\Annotations\AnnotationReader
 */
class AnnotationReader
{
    private $parser;

    private $phpParser;

    private $imports = [];

    public function __construct()
    {
        $this->parser = new DocParser();
        $this->parser->setIgnoreNotImportedAnnotations(true);
        $this->phpParser = new PhpParser();
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

    public function readFromFunction(ReflectionFunction $function) : array
    {
        $context = 'function ' . $function->getName() . '()';
        $this->parser->setTarget(Target::TARGET_METHOD);
        $this->parser->setImports($this->getFunctionImports($function));

        return $this->parser->parse($function->getDocComment(), $context);
    }

    private function getFunctionImports(ReflectionFunction $function) : array
    {

    }

    private function getClassImports(ReflectionClass $class) : array
    {
        $name = $class->getName();
        if (isset($this->imports[$name])) {
            return $this->imports[$name];
        }

        $this->imports[$name] = array_merge(
            $this->phpParser->parseClass($class),
            ['__NAMESPACE__' => $class->getNamespaceName()]
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
                $traitImports = array_merge($traitImports, $this->phpParser->parseClass($trait));
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
                $traitImports = array_merge($traitImports, $this->phpParser->parseClass($trait));
            }
        }

        return array_merge($classImports, $traitImports);
    }
}
