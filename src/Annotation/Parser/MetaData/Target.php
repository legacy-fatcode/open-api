<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser\MetaData;

/**
 * Specifies valid targets for the annotation.
 *
 * @Annotation
 * @Target(Target::TARGET_ALL)
 */
class Target
{
    const TARGET_ALL = 'ALL';
    const TARGET_CLASS = 'CLASS';
    const TARGET_METHOD = 'METHOD';
    const TARGET_FUNCTION = 'FUNCTION';
    const TARGET_PROPERTY = 'PROPERTY';
    const TARGET_ANNOTATION = 'ANNOTATION';

    /**
     * @var string[]
     * @Enum(
     *     Target::TARGET_ALL,
     *     Target::TARGET_CLASS,
     *     Target::TARGET_METHOD,
     *     Target::TARGET_FUNCTION,
     *     Target::TARGET_PROPERTY,
     *     Target::TARGET_ANNOTATION,
     * )
     */
    public $value;
}