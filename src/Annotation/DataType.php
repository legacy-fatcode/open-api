<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Enum;

trait DataType
{
    /**
     * Property type, can be one of: array, integer, number, string, boolean. If none provided docblock will be read to retrieve possible map.
     * @var string
     * @Enum("integer", "number", "string", "boolean", "object")
     * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#data-types
     */
    public $type = 'string';

    /**
     * Primitive types can have optional modifier property `format` which can be any value. The following values are
     * predefined values for supporting specific subtypes:
     *   - int32
     *   - int64
     *   - float
     *   - double
     *   - byte
     *   - binary
     *   - date
     *   - date-time
     *   - password
     *   - email
     *   - alpha
     *   - alpha-numeric
     *   - truthy
     *   - falsy
     *   - uri
     *   - url
     *   - uuid
     *   - ip
     *   - ipv6
     *   - ipv4
     * @var string
     */
    public $format;

    /**
     * Specifies that a parameter is deprecated and SHOULD be transitioned out of usage.
     * @var bool
     */
    public $deprecated = false;

    /**
     * Determines whether property can contain null value.
     * @var bool
     */
    public $nullable;

    /**
     * Examples for user.
     * @var string
     */
    public $examples;

    /**
     * This string SHOULD be a valid regular expression, according to the ECMA 262 regular expression dialect
     * @var string
     */
    public $pattern;

    /**
     * Determines whether property is read only.
     * @var bool
     */
    public $readOnly;

    /**
     * Determines whether property is write only.
     * @var bool
     */
    public $writeOnly;

    /**
     * @var int
     * @see https://json-schema.org/understanding-json-schema/reference/numeric.html#range
     */
    public $maximum;

    /**
     * @var int
     * @see https://json-schema.org/understanding-json-schema/reference/numeric.html#range
     */
    public $minimum;

    /**
     * @var int
     * @see https://json-schema.org/understanding-json-schema/reference/array.html#length
     */
    public $minItems;

    /**
     * @var int
     * @see https://json-schema.org/understanding-json-schema/reference/array.html#length
     */
    public $maxItems;

    /**
     * @var bool
     * @see https://json-schema.org/understanding-json-schema/reference/array.html#uniqueness
     */
    public $uniqueItems;

    /**
     * @var \FatCode\OpenApi\Annotation\Reference|\FatCode\OpenApi\Annotation\Schema
     */
    public $items;
}
