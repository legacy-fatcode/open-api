# OpenAPI [![Build Status](https://travis-ci.org/fat-code/open-api.svg?branch=master)](https://travis-ci.org/fat-code/open-api) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fat-code/open-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fat-code/open-api/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/fat-code/open-api/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/fat-code/open-api/?branch=master)

## Introduction
Open API is a framework for rapid REST applications development. It was created for developers who want to focus on 
theirs application's business domain and processes. Library takes on its shoulders handling and validating the input as 
well as generating output in requested format (json, yml, xml). Everything is supported through meta programming with 
usage of annotation system.
OpenAPI project is compatible with [OpenAPI 3.0](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.0.md) 
with some quirks to simplify development.

## Installation


## Bootstrapping application

```php
<?php declare(strict_types=1);

use FatCode\OpenApi\Annotation as Api;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use FatCode\OpenApi\Application\OnRequestListener;
use Zend\Diactoros\Response;

/**
 * Your entry point/front controller.
 *
 * @Api\Application(
 *     version = "1.0.0",
 *     title = "Your application title",
 *     servers = [
 *         @Api\Server(
 *             id = "development",
 *             port = 80,
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
# examples/hello_world.php
```

The above code is example of simple application that always response with `Hello world` message to all requests. 
This application can be run from terminal with following command:

`open-api run development`

Please take a look closer at `@Api\Application` annotation, it requires 3 parameters:
 - version - version of your api, its always recommended to version your api and keep it compatible with [semver](https://semver.org)
 - title - simply the name of your application (visible in auto-generated documentation)
 - servers - configuration of available servers

More about `@Application` annotation can be found [here](docs/reference/application.md) 
 
# Reference
## `@Application` 


