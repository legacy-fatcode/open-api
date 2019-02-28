<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\SecurityScheme;

use Doctrine\Common\Annotations\Annotation\Target;
use Igni\OpenApi\Annotation\Annotation;

/**
 * @Annotation
 * @Target("ANNOTATION")
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#security-scheme-object
 */
abstract class SecurityScheme extends Annotation
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