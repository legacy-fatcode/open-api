<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\SecurityScheme;

use FatCode\OpenApi\Annotation\Annotation;

/**
 * @Annotation
 */
class OAuthFlows extends Annotation
{
    /**
     * @var OAuthFlow
     */
    public $implicit;

    /**
     * @var OAuthFlow
     */
    public $password;

    /**
     * @var OAuthFlow
     */
    public $clientCredentials;

    /**
     * @var OAuthFlow
     */
    public $authorizationCode;

    protected function getRequiredParameters(): array
    {
        return [];
    }
}
