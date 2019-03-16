<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\PathItem;

use FatCode\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_FUNCTION, Target::TARGET_CLASS, Target::TARGET_METHOD)
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#operationObject
 */
final class Post extends Operation
{
}
