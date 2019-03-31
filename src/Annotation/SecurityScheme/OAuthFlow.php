<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\SecurityScheme;

/**
 * @Annotation
 */
class OAuthFlow
{
    /**
     * The authorization URL to be used for this flow. This MUST be in the form of a URL.
     * @var string
     */
    public $authorizationUrl;

    /**
     * The token URL to be used for this flow. This MUST be in the form of a URL.
     * @var string
     */
    public $tokenUrl;

    /**
     * The URL to be used for obtaining refresh tokens. This MUST be in the form of a URL.
     * @var string
     */
    public $refreshUrl;

    /**
     * The available scopes for the OAuth2 security scheme. A map between the scope name and a short description for it.
     * @var string[]
     */
    public $scopes;

    protected function getRequiredParameters() : array
    {
        return ['authorizationUrl', 'tokenUrl', 'scopes'];
    }
}
