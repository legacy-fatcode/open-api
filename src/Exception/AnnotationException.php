<?php declare(strict_types=1);

namespace Igni\OpenApi\Exception;

use Igni\OpenApi\Annotation\Annotation;
use LogicException;
use Throwable;

abstract class AnnotationException extends LogicException
{
    public static function forMissingProperty(Annotation $annotation, string $propertyName) : Throwable
    {
        $message = "Property {$propertyName} is required and thus must be set in annotation " . get_class($annotation);

        return new class($message)
            extends AnnotationException
            implements UndefinedIndexException {
        };
    }

    public static function forInvalidPropertyValue(Annotation $annotation, string $propertyName) : Throwable
    {
        $message = "Invalid value passed for property {$propertyName} in annotation " . get_class($annotation);

        return new class($message)
            extends AnnotationException
            implements InvalidArgumentException {
        };
    }

    public static function forInvalidPropertyType(Annotation $annotation, $propertyType) : Throwable
    {
        $message = "Invalid property type {$propertyType} in annotation " . get_class($annotation);

        return new class($message)
            extends AnnotationException
            implements UnexpectedValueException {
        };
    }

    public static function forMissingPropertyType(Annotation $annotation, string $propertyName) : Throwable
    {
        $message = "Property {$propertyName} has undefined type in annotation " . get_class($annotation);

        return new class($message)
            extends AnnotationException
            implements UndefinedIndexException {
        };
    }
}
