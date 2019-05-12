<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer\Parser;

class Symbol
{
    private $name;
    private $type;

    public function __construct(string $name, SymbolType $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : SymbolType
    {
        return $this->type;
    }

    public function __toString() : string
    {
        return "{$this->type}:{$this->name}";
    }
}
