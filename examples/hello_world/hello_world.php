<?php declare(strict_types=1);

namespace App\HelloWorld {

    use FatCode\OpenApi\Annotation as Api;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use FatCode\OpenApi\Http\Response;

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
    class Application
    {
    }

    /**
     * @Api\Schema(
     *     title="Greeter schema",
     *     type="object"
     * )
     */
    class Greeter
    {
        /**
         * @Api\Property(type="string")
         */
        public $name;

        public function __construct(string $name)
        {
            $this->name = $name;
        }
    }

    /**
     * @Api\Operation\Get(
     *     description="List all pets",
     *     route="/welcome/{name}",
     *     responses=[
     *         @Api\Response(code=200, schema=@Api\Reference(Greeter::class))
     *     ]
     * )
     */
    function sayHello(ServerRequestInterface $request) : ResponseInterface
    {
        return new Response(200, new Greeter($request->getAttribute('name')));
    }
    # Run with `open-api run development`
}
