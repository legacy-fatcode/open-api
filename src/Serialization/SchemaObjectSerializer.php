<?php declare(strict_types=1);

namespace FatCode\OpenApi\Serialization;

use FatCode\OpenApi\Schema\SchemaObject\SchemaObject;

interface SchemaObjectSerializer
{
    public function __invoke(SchemaObject $schemaObject);
}