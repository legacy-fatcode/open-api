<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

class Token
{
    public const T_INTEGER = 1;
    public const T_STRING = 2;
    public const T_FLOAT = 3;
    public const T_TRUE = 4;
    public const T_NULL = 5;
    public const T_FALSE = 6;

    public const T_AT = 10;
    public const T_IDENTIFIER = 11;
    public const T_OPEN_CURLY_BRACES = 12;
    public const T_CLOSE_CURLY_BRACES = 13;
    public const T_OPEN_BRACKET = 14;
    public const T_CLOSE_BRACKET = 15;
    public const T_OPEN_PARENTHESIS = 16;
    public const T_CLOSE_PARENTHESIS = 17;
    public const T_EQUALS = 18;
    public const T_COMMA = 19;
    public const T_COLON = 20;
    public const T_NAMESPACE_SEPARATOR = 21;
    public const T_ASTERISK = 22;
    public const T_DOCBLOCK = 23;

    public const T_DOCBLOCK_START = 100;
    public const T_DOCBLOCK_END = 101;

    private $type;
    private $value;
    private $index;

    public function __construct(int $index, int $type, $value)
    {
        $this->index = $index;
        $this->type = $type;
        $this->value = $value;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getIndex(): int
    {
        return $this->index;
    }
}
