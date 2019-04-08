<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http\Server\Swoole;

use FatCode\Exception\EnumException;
use FatCode\OpenApi\Http\HttpMethod;
use FatCode\OpenApi\Http\ServerRequest;
use FatCode\OpenApi\Http\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Http\Request;
use Throwable;

use function Zend\Diactoros\marshalMethodFromSapi;
use function Zend\Diactoros\marshalUriFromSapi;
use function Zend\Diactoros\normalizeUploadedFiles;

use const CASE_UPPER;

class SwooleServerRequestFactory implements ServerRequestFactory
{
    /**
     * @param Request $input
     * @return ServerRequestInterface
     */
    public function createServerRequest($input = null) : ServerRequestInterface
    {
        try {
            $body = (string) $input->rawContent();
        } catch (Throwable $throwable) {
            $body = '';
        }

        // Normalize server params
        $serverParams = array_change_key_case($input->server, CASE_UPPER);
        $headers = $input->header ?? [];

        // Http method
        try {
            $httpMethod = HttpMethod::fromValue(marshalMethodFromSapi($serverParams));
        } catch (EnumException $exception) {
            $httpMethod = HttpMethod::GET();
        }

        $request = new ServerRequest(
            marshalUriFromSapi($serverParams, $headers),
            $httpMethod,
            $body,
            $headers,
            normalizeUploadedFiles($input->files ?? []),
            $serverParams
        );

        if (!empty($input->cookie)) {
            $request = $request->withCookieParams($input->cookie);
        }

        if (!empty($input->get)) {
            $request = $request->withQueryParams($input->get);
        }

        return $request;
    }
}
