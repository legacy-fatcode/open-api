<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\PathItem;

use Doctrine\Common\Annotations\Annotation\Target;
use Igni\OpenApi\Annotation\Annotation;

/**
 * @Annotation
 * @Target("ALL")
 */
class Get extends Operation
{
    public $route;
}