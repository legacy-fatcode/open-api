<?php declare(strict_types=1);

namespace PetShop;

use FatCode\HttpServer\HttpStatusCode;
use FatCode\OpenApi\Annotation as Api;
use FatCode\OpenApi\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Your entry point/front controller.
 *
 * @Api\Application(
 *     title = "Your application title",
 *     version = "1.0.0",
 *     servers = [
 *         @Api\Server(
 *             id = "development",
 *             port = 8080,
 *             host = "localhost"
 *         )
 *     ]
 * )
 */
final class Application
{
}

/**
 * @Api\Schema(
 *     title="Pet representation",
 *     type="object"
 * )
 */
class PetSchema
{
    /**
     * @Api\Property(name="pet_id", readOnly=true, nullable=false)
     */
    protected $id;

    /**
     * @Api\Property(type="string", nullable=false)
     */
    protected $name;

    /**
     * @Api\Property(type="integer", minimum=0, maximum=100)
     */
    protected $age;

    /**
     * @Api\Property(type="integer", minimum=0)
     */
    protected $weight;

    /**
     * @Api\Property(type="string", enum=["dog", "cat", "bird"])
     */
    protected $type;

    /**
     * Some additional validation after object initialization
     * @param int $age
     * @return bool
     */
    public function validateAge(int $age) : bool
    {
        return true;
    }
}

/**
 * @Api\PathItem\Post(
 *     route="/pets",
 *     responses=[
 *         @Api\Response(code=200, schema=PetSchema::class)
 *     ],
 *     requestBody=PetSchema::class
 * )
 * @param ServerRequestInterface $request
 * @return ResponseInterface
 */
function createPet(ServerRequestInterface $request, PetSchema $petSchema) : ResponseInterface
{
    return new Response(HttpStatusCode::OK, $petSchema);
}
