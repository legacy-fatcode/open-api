<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http;

use FatCode\OpenApi\Http\Server\OnRequestListener;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\SimpleCache\CacheInterface;

class Router implements OnRequestListener, MiddlewareInterface
{
    private $routes = [];
    private $cache;

    public function __construct(CacheInterface $cache = null)
    {
        $this->cache = $cache;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        return $this->onRequest($request, $handler);
    }

    public function onRequest(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {

    }

    public function put(string $route, callable $controller)
    {

    }

    public function get(string $route, callable $handler)
    {
        $this->routes[] = new Route($route, HttpMethod::GET(), $handler);
    }
}
