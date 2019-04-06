<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http;

use FatCode\OpenApi\Exception\Http\HttpException;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\MessageTrait;

class Response implements ResponseInterface
{
    use MessageTrait;

    private $reasonPhrase;
    private $statusCode;
    private $complete = false;

    public function __construct($body = '', HttpStatusCode $status = null, array $headers = [])
    {
        $status = $status ?? HttpStatusCode::OK();
        $this->stream = Stream::create($body, 'wb+');
        $this->statusCode = $status->getValue();
        $this->reasonPhrase = $status->getPhrase();
        $this->setHeaders($headers);
    }

    public function write(string $body) : void
    {
        if ($this->complete) {
            throw new HttpException('Cannot write to the response, response is already completed.');
        }

        $this->getBody()->write($body);
    }

    public function end() : void
    {
        $this->complete = true;
    }

    public function isComplete() : bool
    {
        return $this->complete;
    }

    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    public function getReasonPhrase() : string
    {
        return $this->reasonPhrase;
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase;
        return $new;
    }

}
