<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Igni\OpenApi\Annotation\Info\Contact;
use Igni\OpenApi\Annotation\Info\License;

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
     * Required fields
     * @var string[]
     */
    protected $_required = ['title', 'version', 'servers'];
}

