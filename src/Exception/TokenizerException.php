<?php declare(strict_types=1);

namespace Igni\OpenApi\Exception;

use Igni\OpenApi\Annotation\Parser\Context;
use LogicException;
use Throwable;

abstract class TokenizerException extends LogicException
{
    public static function forOutOfBounds(int $index) : Throwable
    {
        return new class("{$index} is out of bounds") extends TokenizerException implements OutOfBoundsException {
        };
    }

    public static function forUnexpectedRewindCall() : Throwable
    {
        return new class("Cannot rewind tokenizer in current state.") extends TokenizerException implements RuntimeException {
        };
    }

    public static function forUnknownAnnotationClass(string $name, Context $context) : Throwable
    {
        $message = "Could not find annotation class {$name} used in {$context}." .
            "Please check your composer settings, or use Parser::registerNamespace.";

        return new class($message) extends TokenizerException implements RuntimeException {
        };
    }
}
