<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SplQueue;

class MiddlewarePipeline implements MiddlewareInterface, RequestHandlerInterface
{
    protected $pipeline;

    public function __construct(SplQueue $pipeline = null)
    {
        $this->pipeline = $pipeline ?: new SplQueue();
    }

    public function __clone()
    {
        $this->pipeline = clone $this->pipeline;
    }

    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        return $this->handle($request);
    }

    /**
     * De-queues and executes middleware from the top of the cloned pipeline.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $pipeline = clone $this;
        $middleware = $pipeline->pipeline->dequeue();

        return $middleware->process($request, $pipeline);
    }

    /**
     * PSR-15 Middleware invocation
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $next = new MiddlewareCursor($this->pipeline, $handler);
        return $next($request);
    }
}
