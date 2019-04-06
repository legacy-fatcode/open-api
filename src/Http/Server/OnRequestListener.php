<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface OnRequestListener extends Listener
{
    public function onRequest(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface;
}
