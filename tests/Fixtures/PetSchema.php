<?php declare(strict_types=1);

namespace FatCode\Tests\OpenApi\Fixtures;

use FatCode\OpenApi\Annotation as Api;

/**
 * @Api\Schema(
 *   title="Pet object",
 *   description="Pet description",
 *   minimum=PetSchema::VERSION,
 *   type=PetSchema::class,
 * )
 */
class PetSchema
{
    const VERSION = 1;
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
