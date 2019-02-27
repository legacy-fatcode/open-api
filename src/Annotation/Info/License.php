<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Info;

use Doctrine\Common\Annotations\Annotation\Target;
use Igni\OpenApi\Annotation\Annotation;

/**
 * @Annotation
 * @Target("ANNOTATION")
 */
class License extends Annotation
{
    /**
     * The license name used for the API.
     * @var string
     */
    public $name;

    /**
     * A URL to the license used for the API. MUST be in the format of a URL.
     * @var string
     */
    public $url;

    protected function getRequiredParameters(): array
    {
        return ['name'];
    }
}
