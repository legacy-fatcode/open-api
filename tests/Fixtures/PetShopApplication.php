<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Fixtures;

use Igni\OpenApi\Annotation;

/**
 * @Annotation\Application(
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
