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
    public const TARGET_ALL = 'ALL';
    public const TARGET_CLASS = 'CLASS';
    public const TARGET_METHOD = 'METHOD';
    public const TARGET_FUNCTION = 'FUNCTION';
    public const TARGET_PROPERTY = 'PROPERTY';
    public const TARGET_ANNOTATION = 'ANNOTATION';

    public const TARGETS = [
        self::TARGET_ALL,
        self::TARGET_CLASS,
        self::TARGET_METHOD,
        self::TARGET_FUNCTION,
        self::TARGET_PROPERTY,
        self::TARGET_ANNOTATION,
    ];

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