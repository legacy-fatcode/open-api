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
    /**
     * @Api\PathItem\Get(
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
    public static function getTag(ServerRequestInterface $request)
    {

    }

    /**
     * @Api\PathItem\Get(
     *   route="/pets/{id}",
     *   description="Retrieves pet with given id",
     *   parameters=[
     *     @Api\PathItem\PathParameter(
     *       name="id",
     *       type="number"
     *     ),
     *   ],
     *   @Api\Response(
     *     code="200",
     *     schema=@Api\Reference(Pet::class),
     *     @Api\Link(
     *       name="tag",
     *       operationId="getTag",
     *       @Api\PathItem\PathParameter(name="id", expression="$response.body#/tags")
     *     )
     *   ),
     *   @Api\SecurityScheme\ApiKey(
     *     in="header",
     *     name="ApiToken"
     *   )
     * )
     * @param ServerRequestInterface $request
     */
    public static function getPet(ServerRequestInterface $request)
    {

    }
}