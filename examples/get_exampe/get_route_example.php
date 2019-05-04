<?php declare(strict_types=1);

use FatCode\OpenApi\Annotation as Api;
use FatCode\OpenApi\Http\Response;
use FatCode\OpenApi\Schema\TextPlain;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @Api\Application(
 *     version="1.0.0",
 *     title="Example get route",
 *     servers=[
 *         @Api\Server(port=8080, host="localhost")
 *     ],
 * )
 */
class Application
{
    /**
     * @Api\Operation\Get(
     *     route="/hello/{name:\w}",
     *     responses=[
     *         @Api\Response(code=200, schema=@Api\Reference(TextPlain::class))
     *     ]
     * )
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function sayHello(ServerRequestInterface $request) : ResponseInterface
    {
        // Return text/plain response
        return new Response(200, "Hello: {$request->getAttribute('name')}");
    }
}
