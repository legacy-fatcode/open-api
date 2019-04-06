<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http;

use FatCode\Enum;

/**
 * @method static HttpMethod GET
 * @method static HttpMethod POST
 * @method static HttpMethod PUT
 * @method static HttpMethod DELETE
 * @method static HttpMethod PATCH
 * @method static HttpMethod OPTIONS
 * @method static HttpMethod HEAD
 * @method static HttpMethod CONNECT
 * @method static HttpMethod TRACE
 */
class HttpMethod extends Enum
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';
    public const PATCH = 'PATCH';
    public const OPTIONS = 'OPTIONS';
    public const HEAD = 'HEAD';
    public const CONNECT = 'CONNECT';
    public const TRACE = 'TRACE';
}
