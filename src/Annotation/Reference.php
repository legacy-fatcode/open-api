<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#referenceObject
 */
class Reference extends Annotation
{
    /**
     * @var string
     * @Required
     */
    public $ref;

    protected function getRequiredParameters(): array
    {
        return ['ref'];
    }
}
