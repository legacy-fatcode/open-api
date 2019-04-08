<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http\Server\Swoole;

use FatCode\OpenApi\Http\Server\HttpServerHandler;
use FatCode\OpenApi\Http\Server\HttpServerSettings;
use FatCode\OpenApi\Http\Server\MiddlewarePipeline;
use Swoole\Http\Server;
use RuntimeException;
use Swoole\Runtime as SwooleRuntime;
use Swoole\Http\Request as SwooleHttpRequest;
use Swoole\Http\Response as SwooleHttpResponse;

use function extension_loaded;
use function method_exists;

class SwooleServerHandler implements HttpServerHandler
{
    private $server;
    private $requestFactory;

    public function __construct()
    {
        if (!extension_loaded('swoole')) {
            throw new RuntimeException('Swoole extenstion is missing, please install it and try again.');
        }

        $this->requestFactory = new SwooleServerRequestFactory();
    }

    public function start(HttpServerSettings $settings, MiddlewarePipeline $pipeline) : void
    {
        // Support coroutine if possible.
        if (method_exists(SwooleRuntime::class, 'enableCoroutine')) {
            SwooleRuntime::enableCoroutine(true);
        }

        $this->server = new Server(
            $settings->getAddress(),
            $settings->getPort(),
            SWOOLE_PROCESS,
            SWOOLE_TCP
        );

        $this->server->set($this->translateSettings($settings));
        $this->server->on(
            'Request',
            function (SwooleHttpRequest $request, SwooleHttpResponse $response) use ($pipeline, $settings) {
                $psrRequest = $this->requestFactory->createServerRequest($request);
                $pipeline = clone $pipeline;
                $psrResponse = $pipeline->process($psrRequest, $pipeline);

                // Set headers
                foreach ($psrResponse->getHeaders() as $name => $values) {
                    foreach ($values as $value) {
                        $response->header($name, $value);
                    }
                }

                // Response body.
                $body = $psrResponse->getBody()->getContents();

                // Status code
                $response->status($psrResponse->getStatusCode());

                // Protect server software header.
                $response->header('software-server', '');
                $response->header('server', '');

                // Support gzip/deflate encoding.
                if ($psrRequest->hasHeader('accept-encoding')) {
                    $encoding = explode(
                        ',',
                        strtolower(implode(',', $psrRequest->getHeader('accept-encoding')))
                    );
                    if (in_array('gzip', $encoding, true)) {
                        $response->header('content-encoding', 'gzip');
                        $body = gzencode($body, $settings->getCompressionLevel());
                    } elseif (in_array('deflate', $encoding, true)) {
                        $response->header('content-encoding', 'deflate');
                        $body = gzdeflate($body, $settings->getCompressionLevel());
                    }
                }
                $response->end($body);
            }
        );

        $this->server->start();
    }

    private function translateSettings(HttpServerSettings $settings) : array
    {
        return $settings->toArray();
    }
}
