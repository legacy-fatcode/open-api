<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use FatCode\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_CLASS)
 */
final class Application
{
    /**
     * The title of the application.
     *
     * @Required
     * @var string
     */
    public $title;

    /**
     * The version of the OpenAPI application. Should follow semver.
     *
     * @Required
     * @var string
     */
    public $version;

    /**
     * An array of Server Objects, which provide connectivity information to a target server.
     *
     * @Required
     * @var \FatCode\OpenApi\Annotation\Server[]
     */
    public $servers;

    /**
     * A short description of the application. CommonMark syntax MAY be used for rich text representation.
     *
     * @var string
     */
    public $description;

    /**
     * A URL to the Terms of Service for the API. MUST be in the format of a URL.
     *
     * @var string
     */
    public $termsOfService;

    /**
     * The contact information for the exposed API.
     *
     * @var \FatCode\OpenApi\Annotation\Info\Contact
     */
    public $contact;

    /**
     * The license information for the exposed API.
     *
     * @var \FatCode\OpenApi\Annotation\Info\License
     */
    public $license;

    /**
     * A declaration of which security mechanisms can be used across the API. The list of values includes alternative
     * security requirement objects that can be used. Only one of the security requirement objects need to be satisfied
     * to authorize a request. Individual operations can override this definition.
     *
     * @var \FatCode\OpenApi\Annotation\SecurityScheme\SecurityScheme
     */
    public $security;
}

