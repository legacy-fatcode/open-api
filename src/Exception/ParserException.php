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
}
