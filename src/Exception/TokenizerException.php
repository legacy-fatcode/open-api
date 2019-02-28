<?php declare(strict_types=1);

namespace Igni\OpenApi\Exception;

use LogicException;
use Throwable;

abstract class TokenizerException extends LogicException
{
    public static function forOutOfBonds(int $index) : Throwable
    {
        return new class extends TokenizerException {

        };
    }
}
