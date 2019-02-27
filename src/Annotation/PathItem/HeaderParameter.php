<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\PathItem;

use Doctrine\Common\Annotations\Annotation\Target;
use Igni\OpenApi\Annotation\Annotation;
use Igni\OpenApi\Annotation\Parameter;

/**
 * @Annotation
 * @Target("ANNOTATION")
 */
class HeaderParameter extends Parameter
{
    public $in = 'header';
}