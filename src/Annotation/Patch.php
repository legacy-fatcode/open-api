<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\PathItem;

use FatCode\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_FUNCTION, Target::TARGET_CLASS, Target::TARGET_METHOD)
 */
final class Patch extends Operation
{
    /**
     * Route that points the the resource.
     *
     * @Required
     * @var string
     */
    public $route;
}
