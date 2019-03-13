<?php declare(strict_types=1);

namespace FatCode\Tests\OpenApi\Fixtures;

use FatCode\OpenApi\Annotation as Api;

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
