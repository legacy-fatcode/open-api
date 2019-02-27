<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Fixtures;

use Igni\OpenApi\Annotation as Api;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @Api\Application(
 *     title="Pet shop api",
 *     version="1.0.0",
 *     servers={
 *       @Api\Server(host="localhost", port="80", id="development")
 *     }
 * )
 */
class PetShopApplication
{

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
 * @param ServerRequestInterface $request
 */
function getTag(ServerRequestInterface $request)
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
 * @param ServerRequestInterface $request
 */
function getPet(ServerRequestInterface $request)
{

}