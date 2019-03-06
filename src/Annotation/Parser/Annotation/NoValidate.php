<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser\Annotation;

/**
 * Tells parser whether annotation's properties should be validated.
 * By default parser validates all properties.
 *
 * @Annotation
 * @Target(Target::TARGET_CLASS, Target::TARGET_PROPERTY)
 */
class NoValidate
{
    /**
     * @var boolean
     */
    public $value;
}
