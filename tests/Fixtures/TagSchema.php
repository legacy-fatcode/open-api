<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Fixtures;

use Igni\OpenApi\Annotation as Api;

/**
 * @Api\Schema(
 *   title="Tag object",
 *   description="Tag description"
 * )
 */
class TagSchema
{
    /**
     * @Api\Property(readOnly=true)
     */
    public $id;
    /**
     * @Api\Property(required=true)
     */
    public $name;
}