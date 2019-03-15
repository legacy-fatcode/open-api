<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\Info;

use FatCode\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_ANNOTATION)
 */
class Contact
{
    /**
     * The identifying name of the contact person/organization.
     * @var string
     */
    public $name;

    /**
     * The URL pointing to the contact information. MUST be in the format of a URL.
     * @var string
     */
    public $url;

    /**
     * The email address of the contact person/organization. MUST be in the format of an email address.
     * @var string
     */
    public $email;

    protected function getRequiredParameters(): array
    {
        return [];
    }
}
