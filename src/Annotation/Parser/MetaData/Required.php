<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser\MetaData;

/**
 * Tells parser whether annotation's property is required.
 *
 * @Annotation
 * @Target(Target::TARGET_PROPERTY)
 */
class Required
{
    /**
     * @var boolean
     */
    public $value;
}