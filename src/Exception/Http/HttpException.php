<?php declare(strict_types=1);

namespace FatCode\OpenApi\Exception\Http;

use FatCode\OpenApi\Exception\RuntimeException as FatCodeRuntimeException;
use RuntimeException;

class HttpException extends RuntimeException implements FatCodeRuntimeException
{
}
