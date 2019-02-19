<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\SecurityScheme;

use Igni\OpenApi\Annotation\Annotation;

/**
 * @Annotation
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