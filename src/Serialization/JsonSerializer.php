<?php declare(strict_types=1);

namespace FatCode\OpenApi\Serialization;

use FatCode\OpenApi\Schema\SchemaObject\SchemaObject;

final class JsonSerializer implements SchemaObjectSerializer
{
    /** @var ArraySerializer */
    private $arraySerializer;

    public function __construct(ArraySerializer $arraySerializer)
    {
        $this->arraySerializer = $arraySerializer;
    }

    public function __invoke(SchemaObject $schemaObject) : string
    {
        return json_encode(($this->arraySerializer)($schemaObject));
    }
}