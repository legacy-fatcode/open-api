<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Igni\OpenApi\Annotation\Server\Variable;

/**
 * Extended server annotation
 * @Annotation
 */
class Server extends Annotation
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

    protected function getAttributesSchema() : array
    {
        return [
            'url' => [
                'type' => self::TYPE_STRING,
                'required' => true,
            ],
            'description' => [
                'type' => self::TYPE_STRING
            ],
            'variables' => [
                'type' => [self::TYPE_HASH, Variable::class]
            ],
            'port' => [
                'type' => self::TYPE_STRING
            ],
            'host' => [
                'type' => self::TYPE_STRING
            ],
            'id' => [
                'type' => self::TYPE_STRING,
                'required' => true,
            ],
        ];
    }
}
