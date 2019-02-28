<?php declare(strict_types=1);

namespace Igni\OpenApi\Exception;

use LogicException;
use Throwable;

abstract class TokenizerException extends LogicException
{
    public static function forOutOfBonds() : Throwable
    {
        return new class extends TokenizerException implements OutOfBondsException {

        };
    }
}
