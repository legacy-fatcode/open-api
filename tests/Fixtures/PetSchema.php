<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Fixtures;

use Igni\OpenApi\Annotation as Api;

/**
 * @Api\Schema(
 *   title="Pet object",
 *   description="Pet description",
 * )
 */
class PetSchema
{
    /**
     * @Api\Property(format="uuid", readOnly=true)
     */
    public $id;

    /**
     * @Api\Property(required=true)
     */
    public $name;

    /**
     * @Api\Property(type="array", items=@Api\Reference(Tag::class))
     */
    public $tags;
}
