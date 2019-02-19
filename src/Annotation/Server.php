<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Igni\OpenApi\Annotation\Server\Variable;

/**
 * Extended server annotation
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
    public $url = 'http://{host}:{port}/';

    /**
     * An optional string describing the host designated by the URL.
     * CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * @var Variable[]
     */
    public $variables;

    /**
     * @var string
     */
    public $port;

    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $id;

    protected $_required = ['url', 'name'];
}
