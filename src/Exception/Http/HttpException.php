<?php declare(strict_types=1);

namespace FatCode\OpenApi\Exception\Http;

use FatCode\OpenApi\Exception\RuntimeException as FatCodeRuntimeException;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

abstract class HttpException extends RuntimeException implements FatCodeRuntimeException
{
    abstract public function toResponse() : ResponseInterface;
}
