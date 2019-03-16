<?php declare(strict_types=1);

namespace FatCode\OpenApi\Application;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface OnRequestListener
{
    public function onRequest(ServerRequestInterface $request) : ResponseInterface;
}
