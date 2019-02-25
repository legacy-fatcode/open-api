<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;
use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;

AnnotationRegistry::registerLoader('class_exists');

class AnnotationReader
{
    private $parser;
    private $reader;
    private $namespaces = [
        'Api' => 'Igni\\OpenApi\\Annotation',
    ];

    public function __construct()
    {
        $this->parser = new DocParser();
        $this->parser->setIgnoreNotImportedAnnotations(true);
    }

    public function addNamespace(string $name, string $ns) : void
    {
        $this->namespaces[$name] = $ns;
    }

    public function readFromClass(ReflectionClass $class) : array
    {
        $this->parser->setImports($this->namespaces);
        $this->parser->setTarget(Target::TARGET_CLASS);
        $parsed = $this->parser->parse($class->getDocComment());

        return $parsed;
    }
}
