<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Igni\OpenApi\Annotation\Info\Contact;
use Igni\OpenApi\Annotation\Info\License;
use Igni\OpenApi\Annotation\SecurityScheme\SecurityScheme;

/**
 * @Annotation
 */
class Application extends Annotation
{
    /**
     * The title of the application.
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
     */
    public $version;

    /**
     * @var Server[]
     */
    public $servers;

    /**
     * A declaration of which security mechanisms can be used across the API. The list of values includes alternative
     * security requirement objects that can be used. Only one of the security requirement objects need to be satisfied
     * to authorize a request. Individual operations can override this definition.
     * @var SecurityScheme
     */
    public $security;

    protected function getAttributesSchema() : array
    {
        return [
            'title' => [
                'required' => true,
                'type' => self::TYPE_STRING,
            ],
            'description' => [
                'type' => self::TYPE_STRING,
            ],
            'termsOfService' => [
                'type' => self::TYPE_STRING,
            ],
            'contact' => [
                'type' => Contact::class,
            ],
            'license' => [
                'type' => License::class,
            ],
            'version' => [
                'required' => true,
                'type' => self::TYPE_STRING,
            ],
            'servers' => [
                'required' => true,
                'type' => [Server::class],
            ],
        ];
    }
}

