<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

class Token
{
    public const T_NONE = 1;
    public const T_INTEGER = 2;
    public const T_STRING = 3;
    public const T_FLOAT = 5;
    public const T_TRUE = 6;
    public const T_NULL = 7;
    public const T_FALSE = 8;

    public const T_IDENTIFIER = 10;
    public const T_OPEN_CURLY_BRACES = 11;
    public const T_CLOSE_CURLY_BRACES = 12;
    public const T_OPEN_PARENTHESIS = 13;
    public const T_CLOSE_PARENTHESIS = 14;
    public const T_AT = 15;
    public const T_EQUALS = 16;
    public const T_COMMA = 17;
    public const T_COLON = 18;
    public const T_NAMESPACE_SEPARATOR = 19;

    public const T_DOCBLOCK_START = 20;
    public const T_DOCBLOCK_END = 21;
    public const T_DOCBLOCK_LINE_FEED = 22;

    private $type;
    private $value;
    private $index;

    public function __construct(int $index, int $type, string $value)
    {
        $this->index = $index;
        $this->type = $type;
        $this->value = $value;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getIndex(): int
    {
        return $this->index;
    }
}