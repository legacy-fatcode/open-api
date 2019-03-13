<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_ANNOTATION)
 */
final class Header extends Parameter
{
    public $in = 'header';
}
