<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\SecurityScheme;

use Igni\OpenApi\Annotation\Annotation;

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