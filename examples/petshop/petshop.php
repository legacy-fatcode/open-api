<?php declare(strict_types=1);

namespace PetShop;

use Igni\OpenApi\Annotation as Api;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

require_once 'config/default.php';

/**
 * This application can be run in development mode by typing in terminal:
 * open-api serve --server=development
 *
 * @Api\Application(
 *   version="1.0.0",
 *   title="Pet shop application",
 *   @Api\Server(
 *      id="development",
 *      host=DEVELOPMENT_HOST,
 *      port=DEVELOPMENT_PORT,
 *      description="Development server"
 *   ),
 *   @Api\SecurityScheme\ApiKey(
 *     name="AuthKey",
 *     in="header"
 *   )
 * )
 */
class Application
{
}

/**
 * @Api\Schema(
 *   title="Pet object",
 *   description="Pet description",
 *   required={'name', 'id'}
 * )
 */
class Pet
{
    /**
     * @Api\Property()
     */
    public $name;

    /**
     * @Api\Property(format="uuid", readOnly=true)
     */
    public $id;

    /**
     * @Api\Property()
     */
    public $tag;
}

/**
 * @Api\PathItem\GetRoute(
 *   route="/pet/{id}",
 *   description="Retrieves a pet with an Id",
 *   @Api\PathItem\PathParameter(
 *     name="id",
 *     allow="number"
 *   ),
 *   @Api\Response(
 *     code="200",
 *     schema=@Api\Reference(Pet::class)
 *   )
 * )
 */
function getPet(ServerRequestInterface $request): ResponseInterface
{
    return new Response(200, new Pet($request->getAttribute('id')));
}