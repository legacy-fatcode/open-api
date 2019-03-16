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
}

/**
 * @Api\PathItem\Get(
 *     route="/something/{id}",
 *     parameters=[
 *         @Api\Parameter(name="id", in="path", schema=@Api\Schema(type="int"))
 *     ],
 *     responses=[
 *         @Api\Response(code=200, schema=@Api\Reference(TextPlain::class))
 *     ]
 * )
 *
 * @param ServerRequestInterface $request
 * @return ResponseInterface
 */
function getSomething(ServerRequestInterface $request) : ResponseInterface
{
    return new Response(200, "Hello world");
}


//open-api build ./src
