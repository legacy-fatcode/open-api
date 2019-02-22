<?php declare(strict_types=1);

namespace Igni\OpenApi\Exception;

use Igni\OpenApi\Annotation\Annotation;
use Throwable;

final class AnnotationException
{
    private function __construct()
    {
        // Intentionally left blank.
    }

    public static function forMissingProperty(Annotation $annotation, string $propertyName) : Throwable
    {
        $message = "Property {$propertyName} is required and thus must be set in annotation " . get_class($annotation);

        return new class($message)
            extends \InvalidArgumentException
            implements InvalidArgumentException, OpenApiException {
        };
    }

    public static function forInvalidPropertyValue(Annotation $annotation, string $propertyName) : Throwable
    {
        $message = "Invalid value passed for property {$propertyName} in annotation " . get_class($annotation);

        return new class($message)
            extends \InvalidArgumentException
            implements InvalidArgumentException, OpenApiException {
        };
    }

    public static function forInvalidPropertyType(Annotation $annotation, $propertyType) : Throwable
    {
        $message = "Invalid property type {$propertyType} in annotation " . get_class($annotation);

        return new class($message)
            extends \InvalidArgumentException
            implements InvalidArgumentException, OpenApiException {
        };
    }
}