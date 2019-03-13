<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\Parser\Annotation;

/**
 * Specifies available values for annotation's property.
 *
 * @Annotation
 * @Target(Target::TARGET_PROPERTY)
 */
class Enum
{
    /**
     * @var mixed[]
     */
    public $value;
}
