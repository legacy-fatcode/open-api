<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\SecurityScheme;

use Igni\OpenApi\Annotation\Annotation;

/**
 * @Annotation
 * @Target("ANNOTATION")
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#security-scheme-object
 */
class OAuth2 extends SecurityScheme
{
    /**
     * An object containing configuration information for the flow types supported.
     * @var OAuthFlows
     */
    public $flows;

    public function __construct()
    {
        $this->type = 'oauth2';
    }

    protected function getRequiredParameters() : array
    {
        return ['flows'];
    }
}