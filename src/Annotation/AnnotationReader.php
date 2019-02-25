<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;
use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;

AnnotationRegistry::registerLoader(function(string $className) {
    $args = func_get_args();
    return true;
});

class AnnotationReader
{
    private $parser;
    private $namespaces = [
        'Api' => 'Igni\\OpenApi\\Annotation',
    ];

    public function __construct()
    {
        $this->reader = new \Doctrine\Common\Annotations\AnnotationReader();
        $this->parser = new DocParser();
        //$this->parser->setIgnoreNotImportedAnnotations(true);
    }

    public function addNamespace(string $name, string $ns) : void
    {
        $this->namespaces[$name] = $ns;
    }

    public function readFromClass(ReflectionClass $class) : array
    {
        $this->parser->setImports($this->namespaces);
        $this->parser->setTarget(Target::TARGET_CLASS);
        $doc = $class->getDocComment();
        $parsed = $this->parser->parse($doc);

        return $parsed;
    }
}
