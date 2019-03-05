<?php declare(strict_types=1);

namespace Igni\OpenApi\Exception;

use Igni\OpenApi\Annotation\Parser\Context;
use Igni\OpenApi\Annotation\Parser\Token;
use LogicException;
use Throwable;

abstract class ParserException extends LogicException
{
    public static function forUnexpectedToken(Token $token, Context $context) : Throwable
    {
        $context = $context->getSymbol() ?: (string) $context;
        $message = "Unexpected `{$token}` in {$context} at index: {$token->getIndex()}.";

        return new class($message) extends ParserException implements UnexpectedValueException {
        };
    }

    public static function forUnknownAnnotationClass(string $name, Context $context) : Throwable
    {
        $message = "Could not find annotation class {$name} used in {$context}." .
            "Please check your composer settings, or use Parser::registerNamespace.";

        return new class($message) extends ParserException implements RuntimeException {
        };
    }

    public static function forUsingNonAnnotationClassAsAnnotation(string $class, Context $context) : Throwable
    {
        $message = "Used {$class} as annotation - class is not marked as annotation. Used in {$context}." .
            "Please add `@Annotation` annotation to mark class as annotation class.";

        return new class($message) extends ParserException implements RuntimeException {
        };
    }

    public static function forPropertyValidationFailure(Context $context, array $schema, string $property) : Throwable
    {
        $message = "Property {$property} has failed validation";
        if ($schema['enum']) {
            $message .= ', must be one of: ' . implode(',', $schema['enum']);
        } else {
            $type = $schema['type'];
            if (is_array($type)) {
                $type = end($schema['type']) . '[]';
            }

            $message .= ", must be type of: {$type}";
        }

        if ($schema['required']) {
            $message .= ' and is required';
        }
        $message .= ". Defined in {$context}";

        return new class($message) extends ParserException implements RuntimeException {
        };
    }

}
