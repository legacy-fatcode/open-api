<?php
require_once '../../vendor/autoload.php';

use FatCode\OpenApi\Http\Server;
use FatCode\OpenApi\Http\Router;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use FatCode\OpenApi\Http\Response;

$router = new Router();
$router->get('/{hello}', function (ServerRequestInterface $request) : ResponseInterface {
    return new Response(200, 'Hello Jakub!');
});
$router->post('/{hello}', function (ServerRequestInterface $request) : ResponseInterface {
    return new Response(201, 'New Jakub!');
});

$server = new Server();
$server->use($router);
$server->start('0.0.0.0', 8080);
