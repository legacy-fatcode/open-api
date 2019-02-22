<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;

AnnotationRegistry::registerLoader('class_exists');

class AnnotationParser
{
    private $parser;
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

    public function fromComment(string $docBlock) : array
    {

    }
}