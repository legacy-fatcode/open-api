<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#responseObject
 */
class Response extends Annotation
{
    /**
     * A brief description of the response object. CommonMark syntax MAY be used for rich text representation.
     * @var string
     * @Required
     */
    public $description;

    /**
     * @var \FatCode\OpenApi\Annotation\Header[]
     */
    public $headers;

    /**
     * @var \FatCode\OpenApi\Annotation\Schema|\FatCode\OpenApi\Annotation\Reference
     * @Required
     */
    public $schema;

    /**
     * @var \FatCode\OpenApi\Annotation\Link[]|\FatCode\OpenApi\Annotation\Reference[]
     */
    public $links = [];
}
