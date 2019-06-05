<?php declare(strict_types=1);

namespace FatCode\OpenApi\Serialization;

use FatCode\OpenApi\Schema\SchemaObject\Schema;

final class JsonSerializer
{
    /** @var ArraySerializer */
    private $arraySerializer;

    public function __construct(ArraySerializer $arraySerializer)
    {
        $this->arraySerializer = $arraySerializer;
    }

    public function __invoke(Schema $schema) : string
    {
        return json_encode(($this->arraySerializer)($schema));
    }
}