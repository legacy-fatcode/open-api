<?php declare(strict_types=1);

namespace App;

use FatCode\OpenApi\Annotation as Api;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use FatCode\OpenApi\Application\OnRequestListener;
use Zend\Diactoros\Response;

/**
 * Your entry point/front controller.
 *
 * @Api\Application(
 *     title = "Your application title",
 *     version = "1.0.0",
 *     servers = [
 *         @Api\Server(
 *             id = "development",
 *             port = 8080,
 *             host = "localhost"
 *         )
 *     ]
 * )
 */
class Application implements OnRequestListener
{
    public function onRequest(ServerRequestInterface $request) : ResponseInterface
    {
        return new Response('Hello world');
    }
}

# Run with `open-api run development`
