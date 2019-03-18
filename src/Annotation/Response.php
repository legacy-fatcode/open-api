<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use FatCode\Annotation\Required;

/**
 * @Annotation
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#responseObject
 */
class Response
{
    /**
     * A brief description of the response object. CommonMark syntax MAY be used for rich text representation.
     * @var string
     * @Required
     */
    public $description;

    /**
     * The code that is valid for this response.
     * @var int
     * @Required
     */
    public $code = 200;

    /**
     * @var Header[]
     */
    public $headers;

    /**
     * @var Reference
     * @Required
     */
    public $schema;

    /**
     * @var Reference[]
     */
    public $links = [];
}
