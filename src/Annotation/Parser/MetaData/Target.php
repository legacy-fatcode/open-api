<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser\MetaData;

class Target
{
    const TARGET_ALL = 'ALL';
    const TARGET_CLASS = 'CLASS';
    const TARGET_METHOD = 'METHOD';
    const TARGET_FUNCTION = 'FUNCTION';
    const TARGET_PROPERTY = 'PROPERTY';
    const TARGET_ANNOTATION = 'ANNOTATION';
}