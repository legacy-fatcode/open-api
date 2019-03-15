<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\PathItem;

use FatCode\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_FUNCTION, Target::TARGET_CLASS, Target::TARGET_METHOD)
 */
class Get extends Operation
{
    public $route;
}
