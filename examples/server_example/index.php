<?php
require_once '../../vendor/autoload.php';

use FatCode\OpenApi\Http\HttpServer;
use FatCode\OpenApi\Http\HttpStatusCode;
use FatCode\OpenApi\Http\Response;
use FatCode\OpenApi\Http\Server\HttpServerSettings;
use FatCode\OpenApi\Http\Server\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

$router = new Router();
$router->get('/{hello}', function (ServerRequestInterface $request) : ResponseInterface {
    return new Response('Hello Jakub!');
});

$router->post('/{hello}', function (ServerRequestInterface $request) : ResponseInterface {
    return new Response('New Jakub!', HttpStatusCode::CREATED());
});

$server = new HttpServer(new HttpServerSettings('0.0.0.0', 8080));
$server->use($router);
$server->start();
