<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use FatCode\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_PROPERTY, Target::TARGET_ANNOTATION)
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#schemaObject
 */
class Property
{
    use DataType;

    /**
     * The name of the parameter. Parameter names are case sensitive. If none provided, annotated field name will get assigned.
     * @var string
     * @Required
     */
    public $name;

    /**
     * A brief description of the parameter. This could contain examples of use. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * Determines whether this parameter is mandatory. If the parameter location is "path", this property is REQUIRED
     * and its value MUST be true. Otherwise, the property MAY be included and its default value is false.
     * @var bool
     */
    public $required = true;
}
