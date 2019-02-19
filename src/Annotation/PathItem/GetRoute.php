<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\PathItem;

use Igni\OpenApi\Annotation\Annotation;

/**
 * @Annotation
 */
class GetRoute extends Operation
{
    public $route;
}