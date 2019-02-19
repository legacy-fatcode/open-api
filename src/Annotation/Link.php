<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

/**
 * @Annotation
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
     * @var Parameter[]
     */
    public $parameters;

    /**
     * A description of the link. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;
}