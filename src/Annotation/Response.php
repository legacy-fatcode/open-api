<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

/**
 * @Annotation
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#responseObject
 */
class Response extends Annotation
{
    /**
     * A brief description of the response object. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * @var Header[]
     */
    public $headers;

    /**
     * @var Schema|Reference
     */
    public $schema;

    /**
     * @var array
     */
    public $links = [];

    protected function getRequiredParameters(): array
    {
        return ['description', 'schema'];
    }
}