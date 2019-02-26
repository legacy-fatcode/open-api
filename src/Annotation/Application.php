<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;
use Igni\OpenApi\Annotation\Info\Contact;
use Igni\OpenApi\Annotation\Info\License;
use Igni\OpenApi\Annotation\SecurityScheme\SecurityScheme;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Application extends Annotation
{
    /**
     * The title of the application.
     * @Required
     * @var string
     */
    public $title;

    /**
     * A short description of the application. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * A URL to the Terms of Service for the API. MUST be in the format of a URL.
     * @var string
     */
    public $termsOfService;

    /**
     * The contact information for the exposed API.
     * @var Contact
     */
    public $contact;

    /**
     * The license information for the exposed API.
     * @var License
     */
    public $license;

    /**
     * The version of the OpenAPI application.
     * @var string
     * @Required
     */
    public $version;

    /**
     * @var \Igni\OpenApi\Annotation\Server[]
     * @Required
     */
    public $servers;

    /**
     * A declaration of which security mechanisms can be used across the API. The list of values includes alternative
     * security requirement objects that can be used. Only one of the security requirement objects need to be satisfied
     * to authorize a request. Individual operations can override this definition.
     * @var SecurityScheme
     */
    public $security;
}

