<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Zend\Diactoros\RequestTrait;
use Zend\Diactoros\Uri;

class Request implements RequestInterface
{
    use RequestTrait;

    /** @var StreamInterface */
    protected $stream;

    public function __construct(
        $uri = null,
        HttpMethod $method = null,
        $body = 'php://temp',
        array $headers = []
    ) {
        $this->validateUri($uri);
        $this->setHeaders($headers);
        $this->method = $method ? $method->getValue() : HttpMethod::GET()->getValue();
        if ($uri instanceof UriInterface) {
            $this->uri = $uri;
        } else {
            $this->uri = $uri ? new Uri((string) $uri) : new Uri();
        }
        $this->stream = Stream::create($body, 'wb+');

        $headers['Host'] = $headers['Host'] ?? [$this->getHostFromUri()];
    }

    private function validateUri($uri) : void
    {
        if (!$uri instanceof UriInterface && !is_string($uri) && null !== $uri) {
            throw new InvalidArgumentException(
                'Invalid URI provided; must be null, a string, or a Psr\Http\Message\UriInterface instance'
            );
        }
    }
}
