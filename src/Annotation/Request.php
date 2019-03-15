<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

/**
 * @Annotation
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#request-body-object
 */
class Request
{
    /**
     * A brief description of the request body. This could contain examples of use. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * Recognized http headers.
     *
     * @var Header[]
     */
    public $headers;

    /**
     * Reference pointing to input object.
     *
     * @var Reference
     * @Required
     */
    public $schema;

    /**
     * @var Type[]
     */
    public $links = [];
}
