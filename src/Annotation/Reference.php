<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use FatCode\Annotation\Required;

/**
 * @Annotation
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#referenceObject
 */
class Reference implements Type
{
    /**
     * @var string
     * @Required
     */
    public $ref;
}
