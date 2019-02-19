<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

/**
 * @Annotation
 */
class Server
{
    /**
     * A URL to the target host. This URL supports Server Variables and MAY be relative,
     * to indicate that the host location is relative to the location where the OpenAPI
     * document is being served. Variable substitutions will be made when a variable
     * is named in {brackets}.
     *
     * @var string
     */
    public $url;

    /**
     * An optional string describing the host designated by the URL.
     * CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * @var
     */
    public $variables;
}
