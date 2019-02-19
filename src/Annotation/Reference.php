<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

/**
 * @Annotation
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#referenceObject
 */
class Reference extends Annotation
{
    public $ref;

    protected function getRequiredParameters(): array
    {
        return ['ref'];
    }
}