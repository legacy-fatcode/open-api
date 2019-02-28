<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\SecurityScheme;

use Igni\OpenApi\Annotation\Annotation;

/**
 * @Annotation
 * @Target("ANNOTATION")
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#security-scheme-object
 */
class Http extends SecurityScheme
{
    /**
     * The name of the HTTP Authorization scheme to be used in the Authorization header as defined in RFC7235.
     * @var string
     * @see https://tools.ietf.org/html/rfc7235#section-5.1
     */
    public $scheme;

    /**
     * A hint to the client to identify how the bearer token is formatted. Bearer tokens are usually generated by
     * an authorization server, so this information is primarily for documentation purposes.
     * @var string
     */
    public $bearerFormat;

    public function __construct()
    {
        $this->type = 'http';
    }

    protected function getRequiredParameters() : array
    {
        return ['scheme'];
    }
}