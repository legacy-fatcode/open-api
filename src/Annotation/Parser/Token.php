<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\Parser;

class Token
{
    public const T_NONE = 0;
    public const T_INTEGER = 1;
    public const T_STRING = 2;
    public const T_FLOAT = 3;
    public const T_FALSE = 4;
    public const T_TRUE = 5;
    public const T_NULL = 6;

    public const T_AT = 10;
    public const T_IDENTIFIER = 11;
    public const T_OPEN_BRACKET = 12;
    public const T_CLOSE_BRACKET = 13;
    public const T_OPEN_PARENTHESIS = 14;
    public const T_CLOSE_PARENTHESIS = 15;
    public const T_COMMA = 16;
    public const T_EQUALS = 17;
    public const T_NAMESPACE_SEPARATOR = 18;
    public const T_COLON = 19;
    public const T_EOL = 20;

    private $type;
    private $value;
    private $position;

    public function __construct(int $position, int $type, string $value)
    {
        $this->position = $position;
        $this->type = $type;
        $this->value = $value;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getValue() : string
    {
        return $this->value;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function __toString() : string
    {
        return $this->value;
    }
}
