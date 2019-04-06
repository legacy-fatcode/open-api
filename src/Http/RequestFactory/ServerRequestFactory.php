<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http\RequestFactory;

use Psr\Http\Message\ServerRequestInterface;

interface ServerRequestFactory
{
    public function createServerRequest($input = null) : ServerRequestInterface;
}
