<?php declare(strict_types=1);

namespace App\HelloWorld {

    use FatCode\OpenApi\Annotation as Api;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use FatCode\OpenApi\Application\OnRequestListener;
    use Zend\Diactoros\Response;

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
    class Application extends Response implements OnRequestListener
    {
        public function onRequest(ServerRequestInterface $request) : ResponseInterface
        {
            return new Response('Hello world');
        }
    }

    /**
     * @Api\Schema(
     *     type="object"
     * )
     */
    class Greeter
    {

    }

    /**
     * @Api\Operation\Get(
     *     description="List all pets",
     *     route="/pets",
     *     responses=[
     *         @Api\Response(schema=@Api\Reference(Greeter::class))
     *     ]
     * )
     */
    function sayHello() {

    }
    # Run with `open-api run development`
}
