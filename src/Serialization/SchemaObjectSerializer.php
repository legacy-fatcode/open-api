<?php declare(strict_types=1);

namespace FatCode\OpenApi\Serialization;

use FatCode\OpenApi\Schema\SchemaObject;

final class SchemaObjectSerializer
{
    public function serializeIntoArray(SchemaObject $object) : array
    {
        //
    }

    public function serializeIntoJson(Object $object) : string
    {
        return json_encode($this->serializeIntoArray($object));
    }
}