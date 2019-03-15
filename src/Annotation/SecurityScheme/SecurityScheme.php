<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\SecurityScheme;

use FatCode\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_ANNOTATION)
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#security-scheme-object
 */
abstract class SecurityScheme
{
    /**
     * The type of the security scheme. Valid values are "apiKey", "http", "oauth2", "openIdConnect".
     * @var string
     */
    public $type;

    /**
     * A short description for security scheme. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;
}
