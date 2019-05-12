<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use ArrayIterator;
use FatCode\OpenApi\Analyzer\Parser\Symbol;
use FatCode\OpenApi\Analyzer\Parser\SymbolType;
use Iterator;
use IteratorAggregate;

class Project implements IteratorAggregate
{
    /**
     * @var Symbol[]
     */
    private $symbols;

    public function addSymbol(Symbol ...$symbols) : void
    {
        foreach ($symbols as $symbol) {
            $this->symbols[] = $symbol;
        }
    }

    public function getSymbols() : array
    {
        return $this->symbols;
    }

    public function getIterator() : Iterator
    {
        return new ArrayIterator($this->symbols);
    }

    public function listDeclaredFunctions() : iterable
    {
        foreach ($this->symbols as $symbol) {
            if ($symbol->getType() === SymbolType::TYPE_FUNCTION()) {
                yield $symbol;
            }
        }
    }

    public function listDeclaredClasses() : iterable
    {
        foreach ($this->symbols as $symbol) {
            if ($symbol->getType() === SymbolType::TYPE_CLASS()) {
                yield $symbol;
            }
        }
    }
}
