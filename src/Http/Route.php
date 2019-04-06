<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http;

class Route
{
    private $path;
    private $method;
    private $handler;

    public function __construct(string $path, HttpMethod $method, $handler)
    {
        $this->path = $path;
        $this->method = $method;
        $this->handler = $handler;
    }

    public function getPath() : string
    {
        return $this->path;
    }

    public function getMethod() : HttpMethod
    {
        return $this->method;
    }

    public function getHandler()
    {
        return $this->handler;
    }
}
