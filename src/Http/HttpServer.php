<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http;

use FatCode\OpenApi\Http\Server\HttpServerHandler;
use FatCode\OpenApi\Http\Server\HttpServerSettings;
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
        $this->middleware = new SplQueue();
    }

    /**
     * @param callable|MiddlewareInterface $middleware
     */
    public function use($middleware) : void
    {
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

        $this->handler->start($this->settings, $this->middleware);

        if (isset($this->stopListener)) {
            ($this->stopListener)($this);
        }
    }
}
