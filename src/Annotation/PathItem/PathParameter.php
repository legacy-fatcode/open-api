<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\PathItem;

use Igni\OpenApi\Annotation\Annotation;
use Igni\OpenApi\Annotation\Parameter;

/**
 * @Annotation
 */
class PathParameter extends Parameter
{
    public $in = 'path';
    public $allow;
}