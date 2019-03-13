<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\PathItem;

use Doctrine\Common\Annotations\Annotation\Target;
use FatCode\OpenApi\Annotation\Annotation;
use FatCode\OpenApi\Annotation\Parameter;

/**
 * @Annotation
 * @Target("ANNOTATION")
 */
class HeaderParameter extends Parameter
{
    public $in = 'header';
}
