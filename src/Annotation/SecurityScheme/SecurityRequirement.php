<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\SecurityScheme;

use FatCode\OpenApi\Annotation\Annotation;

/**
 * Keeps the required security schemes to execute given operation.
 * @Annotation
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#security-requirement-object
 */
final class SecurityRequirement extends Annotation
{
    public $requirement;

    public function __construct(array $values)
    {
        $this->requirement = $values;
    }
}
