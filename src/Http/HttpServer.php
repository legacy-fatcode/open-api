<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http;

use FatCode\OpenApi\Exception\Http\ServerException;
use FatCode\OpenApi\Http\Server\CallableMiddleware;
use FatCode\OpenApi\Http\Server\ErrorMiddleware;
use FatCode\OpenApi\Http\Server\HttpServerHandler;
use FatCode\OpenApi\Http\Server\HttpServerSettings;
use FatCode\OpenApi\Http\Server\MiddlewarePipeline;
use FatCode\OpenApi\Http\Server\SwooleServerHandler;
use Psr\Http\Server\MiddlewareInterface;
use SplQueue;

class HttpServer
{
    private $middleware;
    private $settings;
    private $handler;
    private $errorListener;
    private $startListener;
    private $stopListener;

    public function __construct(HttpServerSettings $settings = null, HttpServerHandler $handler = null)
    {
        $this->settings = $settings ?? new HttpServerSettings();
        $this->handler = $handler ?? new SwooleServerHandler();
        $this->middleware = [];
    }

    /**
     * @param callable|MiddlewareInterface $middleware
     * @example
     * $server->use(function(ServerRequestInterface $request, callable $next) : ResponseInterface {
     *     $next($request);
     *     return new Response('Hello!');
     * });
     */
    public function use($middleware) : void
    {
        if ($middleware instanceof MiddlewareInterface) {
            if (!is_callable($middleware)) {
                throw ServerException::forInvalidMiddleware($middleware);
            }
            $middleware = new CallableMiddleware($middleware);
        }

        $this->middleware->enqueue($middleware);
    }

    public function onError(callable $handler) : void
    {
        $this->errorListener = $handler;
    }

    public function onStart(callable $handler) : void
    {
        $this->startListener = $handler;
    }

    public function onStop(callable $handler) : void
    {
        $this->stopListener = $handler;
    }

    public function start() : void
    {
        if (isset($this->startListener)) {
            ($this->startListener)($this);
        }

        $pipeline = new SplQueue();
        $pipeline->enqueue(new ErrorMiddleware($this->errorListener));
        foreach ($this->middleware as $middleware) {
            $pipeline->enqueue($middleware);
        }
        $this->handler->start($this->settings, new MiddlewarePipeline($pipeline));

        if (isset($this->stopListener)) {
            ($this->stopListener)($this);
        }
    }
}
