<?php declare(strict_types=1);

/**
 * Some docblock
 */
namespace PetShop;

use Igni\OpenApi\Annotation as Api;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

require_once 'config/default.php';



/**
 * This application can be run in development mode by typing in terminal:
 * open-api serve --server=development
 * open-api mock
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
 *   title="Tag object",
 *   description="Pet description"
 * )
 */
class Tag
{
    /**
     * @Api\Property(readOnly=true)
     */
    public $id;
    /**
     * @Api\Property(required=true)
     */
    public $name;
}

/**
 * @Api\Schema(
 *   title="Pet object",
 *   description="Pet description",
 * )
 */
class Pet
{
    /**
     * @Api\Property(format="uuid", readOnly=true)
     */
    public $id;

    /**
     * @Api\Property(required=true)
     */
    public $name;

    /**
     * @Api\Property(type="array", items=@Api\Reference(Tag::class))
     */
    public $tags;
}


/**
 * @Api\PathItem\GetRoute(
 *   route="/tag/{id}",
 *   description="Retrieves tag with given id",
 *   @Api\PathItem\PathParameter(
 *     name="id",
 *     allow="number"
 *   ),
 *   @Api\Response(
 *     code="200",
 *     schema=@Api\Reference(Tag::class)
 *   )
 * )
 */
function getTag()
{

}

/**
 * @Api\PathItem\GetRoute(
 *   route="/pets/{id}/twojastara/z/toba/{nie}/gada",
 *   description="Retrieves pet with given id",
 *   @Api\PathItem\PathParameter(
 *     name="id",
 *     type="number"
 *   ),
 *   @Api\PathItem\PathParameter(
 *      name="nie",
 *      type="string"
 *   )
 *   @Api\Response(
 *     code="200",
 *     schema=@Api\Reference(Pet::class),
 *     @Api\Link(
 *       name="tag",
 *       operationId="getTag",
 *       @Api\PathItem\PathParameter(name="id", expression="$response.body#/tags")
 *     )
 *   )
 * )
 */
function getPet(ServerRequestInterface $request)
{

}