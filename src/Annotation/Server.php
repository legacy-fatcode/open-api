<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use FatCode\Annotation\Target;
use FatCode\OpenApi\Annotation\Server\Variable;

/**
 * Server definition.
 *
 * @Annotation
 * @Target(Target::TARGET_ANNOTATION)
 */
class Server
{
    /**
     * @Required
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $port = '80';

    /**
     * @var string
     */
    public $host = 'localhost';

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
     * A URL to the target host. This URL supports Server Variables and MAY be relative,
     * to indicate that the host location is relative to the location where the OpenAPI
     * document is being served. Variable substitutions will be made when a variable
     * is named in {brackets}.
     *
     * @var string
     */
    public $url = 'http://{host}:{port}/';

    public function getUrl() : string
    {
        return $this->url;
    }
}
