<?php declare(strict_types=1);

namespace FatCode\OpenApi\SchemaPrimitive;

use FatCode\OpenApi\SchemaObject\DataFormat;
use FatCode\OpenApi\SchemaObject\DataType;

interface SchemaPrimitive
{
    public function getType() : DataType;

    public function getFormat() : DataFormat;

    public function getValue();
}