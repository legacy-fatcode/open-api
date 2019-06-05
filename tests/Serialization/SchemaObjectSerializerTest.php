<?php declare(strict_types=1);

namespace FatCode\OpenApiTest\Serialization;

use FatCode\OpenApi\SchemaObject\SchemaObject;

class SchemaObjectSerializer extends TestCase
{
    public function serializeIntoArray(SchemaObject $object) : array
    {
        // get all constructor parameters (type hint + name)

        // construct an array of [name => call_getter(object, name), ...]
    }

    public function serializeIntoJson(Object $object) : string
    {
        return json_encode($this->serializeIntoArray($object));
    }
}