<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser\MetaData;

/**
 * Tells parser whether annotation's properties should be validated.
 * By default parser validates all properties.
 *
 * @Annotation
 * @Target(Target::TARGET_CLASS)
 */
class NoValidate
{
    /**
     * @var boolean
     */
    public $value;
}