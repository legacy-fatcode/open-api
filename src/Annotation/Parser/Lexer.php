<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

use Doctrine\Common\Lexer\AbstractLexer;

final class Lexer extends AbstractLexer
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

    private const TOKENS_MAP = [
        '@'  => self::T_AT,
        ','  => self::T_COMMA,
        '('  => self::T_OPEN_PARENTHESIS,
        ')'  => self::T_CLOSE_PARENTHESIS,
        '['  => self::T_OPEN_BRACKET,
        ']'  => self::T_CLOSE_BRACKET,
        '='  => self::T_EQUALS,
        ':'  => self::T_COLON,
        '\\' => self::T_NAMESPACE_SEPARATOR,
        'true'  => self::T_TRUE,
        'false' => self::T_FALSE,
        'null'  => self::T_NULL,
    ];

    public function __construct(string $input)
    {
        $this->setInput($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function getCatchablePatterns() : array
    {
        return [
            // Identifier or const
            '[a-z_\\\][a-z0-9_\:\\\]*[a-z_][a-z0-9_]*',
            // Numbers
            '(?:[+-]?[0-9]+(?:[\.][0-9]+)*)(?:[eE][+-]?[0-9]+)?',
            // Strings
            '(["\'])(?:\\\1|[^\1])+?\1',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getNonCatchablePatterns() : array
    {
        return ['\s+', '\t+', '\*+', '(.)'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getType(&$value)
    {
        $type = self::T_NONE;

        // Handle strings
        if ($value[0] === '"' || $value[0] === '\'') {
            $value = stripslashes(substr($value, 1, -1));
            return self::T_STRING;
        }

        if ($value[0] === '_' || $value[0] === '\\' || ctype_alpha($value[0])) {
            return self::T_IDENTIFIER;
        }

        $key = strtolower($value);
        if (isset(self::TOKENS_MAP[$key])) {
            return self::TOKENS_MAP[$key];
        }

        // Numbers
        if (is_numeric($value)) {
            return (strpos($value, '.') !== false || stripos($value, 'e') !== false)
                ? self::T_FLOAT : self::T_INTEGER;
        }

        return $type;
    }
}
