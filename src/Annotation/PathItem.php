<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

/**
 * @Annotation
 */
class PathItem extends Annotation
{
    /**
     * An optional, string summary, intended to apply to all operations in this path.
     * @var string
     */
    public $summary;

    /**
     * An optional, string description, intended to apply to all operations in this path. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * A definition of a GET operation on this path.
     * @var
     */
    public $get;

    public $post;

    public $put;

    public $delete;

    public $options;

    public $head;

    public $path;

    public $trace;

    public $parameters = [];

    protected function getRequiredParameters(): array
    {
        return [];
    }
}