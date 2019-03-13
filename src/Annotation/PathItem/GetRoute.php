<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\PathItem;

use Doctrine\Common\Annotations\Annotation\Target;
use FatCode\OpenApi\Annotation\Annotation;

/**
 * @Annotation
 * @Target("ALL")
 */
class Get extends Operation
{
    public $route;
}
