<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer\Parser;

use FatCode\Enum;

/**
 * @method static SymbolType TYPE_CLASS
 * @method static SymbolType TYPE_FUNCTION
 * @method static SymbolType TYPE_NAMESPACE
 * @method static SymbolType TYPE_METHOD
 * @method static SymbolType TYPE_INTERFACE
 */
class SymbolType extends Enum
{
    public const TYPE_CLASS = 'class';
    public const TYPE_FUNCTION = 'function';
    public const TYPE_NAMESPACE = 'namespace';
    public const TYPE_METHOD = 'method';
    public const TYPE_INTERFACE = 'interface';

    public function __toString() : string
    {
        return $this->getValue();
    }
}
