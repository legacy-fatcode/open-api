<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_ANNOTATION)
 */
class Link extends Parameter
{
    /**
     * Name of the link placed in the documentation
     * @var string
     */
    public $name;

    /**
     * The name of an existing, resolvable OAS operation, as defined with a unique operationId.
     * @var string
     */
    public $operationId;

    /**
     * @var \FatCode\OpenApi\Annotation\Parameter[]
     */
    public $parameters;

    /**
     * A description of the link. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;
}
