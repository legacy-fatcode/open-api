<?php declare(strict_types=1);

namespace FatCode\OpenApi\Schema\SchemaPrimitive;

use FatCode\OpenApi\Schema\SchemaObject\DataFormat;
use FatCode\OpenApi\Schema\SchemaObject\DataType;
use FatCode\OpenApi\Schema\SchemaObject\Schema;

interface SchemaPrimitive extends Schema
{
    public function getType() : DataType;

    public function getFormat() : DataFormat;

    public function getValue();
}