<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\DocParser;
use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;
use Doctrine\Common\Annotations\SimpleAnnotationReader;

AnnotationRegistry::registerLoader('class_exists');

class AnnotationReader
{
    private $reader;

    public function __construct()
    {
        $this->reader = new CachedReader(
            new \Doctrine\Common\Annotations\AnnotationReader(),
        );
    }

    public function readFromClass(ReflectionClass $class) : array
    {
        $parsed = $this->reader->getClassAnnotations($class);

        return $parsed;
    }
}
