<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

/**
 * @Annotation
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#parameterObject
 */
class Parameter extends Annotation
{
    /**
     * The name of the parameter. Parameter names are case sensitive.
     * @var string
     */
    public $name;

    /**
     * A brief description of the parameter. This could contain examples of use. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * Used in links
     * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#runtime-expressions
     * @var string
     */
    public $expression;

    /**
     * Determines whether this parameter is mandatory. If the parameter location is "path", this property is REQUIRED
     * and its value MUST be true. Otherwise, the property MAY be included and its default value is false.
     * @var bool
     */
    public $required = true;

    /**
     * Specifies that a parameter is deprecated and SHOULD be transitioned out of usage.
     * @var bool
     */
    public $deprecated = false;

    /**
     * The location of the parameter. Possible values are "query", "header", "path" or "cookie".
     * @var string
     */
    public $in;

    /**
     * The schema defining the type used for the parameter.
     * @var Schema|Reference
     */
    public $schema;

    public $examples;

    protected function getRequiredParameters() : array
    {
        return ['name', 'in', 'required'];
    }
}