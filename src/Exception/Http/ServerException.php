<?php declare(strict_types=1);

namespace FatCode\OpenApi\Exception\Http;

class ServerException extends HttpException
{
    public static function forInvalidPidFile(string $pid) : self
    {
        return new self("PID file `{$pid}` must be writable.");
    }
}
