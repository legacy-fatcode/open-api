<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use FatCode\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_ANNOTATION)
 */
final class Header extends Parameter
{
    public $in = 'header';
}
