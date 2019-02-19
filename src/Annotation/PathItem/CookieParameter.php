<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\PathItem;

use Igni\OpenApi\Annotation\Annotation;
use Igni\OpenApi\Annotation\Parameter;

/**
 * @Annotation
 */
class CookieParameter extends Parameter
{
    public $in = 'cookie';
}