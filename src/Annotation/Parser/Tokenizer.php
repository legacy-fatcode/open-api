<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\Parser;

use FatCode\OpenApi\Exception\TokenizerException;
use Iterator;

use function count;

final class Tokenizer implements Iterator
{
    private const PATTERNS = [
        // String, single quoted or double quoted with escape support
        '("(?:\\\"|[^"])+"|\'(?:\\\'|[^\'])+\')',
        // Identifier or const
        '([a-z_\\\][a-z0-9_\:\\\]*[a-z_][a-z0-9_]*)',
        // Integer or float
        '((?:[+-]?[0-9]+(?:[\.][0-9]+)*)(?:[eE][+-]?[0-9]+)?)',
        // New line
        '(\n+)',
        // Ignored tokens
        '\h+',
        '\*+',
        // Left overs
        '(.)',
    ];

    private const FLAGS = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE;

    private const TYPE_MAP = [
        PHP_EOL => Token::T_EOL,
        '@'  => Token::T_AT,
        ','  => Token::T_COMMA,
        '('  => Token::T_OPEN_PARENTHESIS,
        ')'  => Token::T_CLOSE_PARENTHESIS,
        '['  => Token::T_OPEN_BRACKET,
        ']'  => Token::T_CLOSE_BRACKET,
        '='  => Token::T_EQUALS,
        ':'  => Token::T_COLON,
        '\\' => Token::T_NAMESPACE_SEPARATOR,
        'true'  => Token::T_TRUE,
        'false' => Token::T_FALSE,
        'null'  => Token::T_NULL,
    ];

    private $input;
    private $regex;
    private $tokens = [];
    private $cursor = 0;
    private $length = 0;

    public function __construct(string $input)
    {
        $this->regex = sprintf(
            '/%s/ix',
            implode('|', self::PATTERNS)
        );

        $this->input = $input;
        $this->tokenize();
    }

    /**
     * @return Token[]
     */
    public function getTokens() : array
    {
        return $this->tokens;
    }

    protected function tokenize() : void
    {
        $matches = $matches = preg_split($this->regex, $this->input, -1, self::FLAGS);
        foreach ($matches as $match) {
            $token = $this->createToken($match[0], $match[1]);
            $this->tokens[] = $token;
        }

        $this->length = count($this->tokens);
    }


    private function createToken($value, int $position) : Token
    {
        // Handle strings
        if ($value[0] === '"' || $value[0] === '\'') {
            return new Token($position, Token::T_STRING, stripslashes(substr($value, 1, -1)));
        }

        // Take type from the map
        $key = strtolower($value);
        if (isset(self::TYPE_MAP[$key])) {
            return new Token($position, self::TYPE_MAP[$key], $value);
        }

        // Numbers
        if (is_numeric($value)) {
            if (strpos($value, '.') !== false || stripos($value, 'e') !== false) {
                return new Token($position, Token::T_FLOAT, $value);
            }
            return new Token($position, Token::T_INTEGER, $value);
        }

        // Identifier
        if ($value[0] === '_' || $value[0] === '\\' || ctype_alpha($value[0])) {
            return new Token($position, Token::T_IDENTIFIER, $value);
        }

        // Whateva
        return new Token($position, Token::T_NONE, $value);
    }

    public function seek(int $type) : bool
    {
        for ($this->cursor; $this->cursor < $this->length; $this->cursor++) {
            if ($this->current()->getType() === $type) {
                return true;
            }
        }

        return false;
    }

    public function seekAny(int ...$types): bool
    {
        for ($this->cursor; $this->cursor < $this->length; $this->cursor++) {
            if (in_array($this->current()->getType(), $types)) {
                return true;
            }
        }

        return false;
    }

    public function at(int $index) : Token
    {
        if (!isset($this->tokens[$index])) {
            throw TokenizerException::forOutOfBounds($index);
        }

        return $this->tokens[$index];
    }

    public function first() : Token
    {
        return $this->tokens[0];
    }

    public function last() : Token
    {
        return $this->tokens[$this->cursor - 1];
    }

    public function current() : Token
    {
        return $this->tokens[$this->cursor];
    }

    public function prev() : void
    {
        $this->cursor--;
        if ($this->cursor < 0) {
            $this->cursor = 0;
        }
    }

    public function next() : void
    {
        $this->cursor++;
    }

    public function key() : int
    {
        return $this->cursor;
    }

    public function valid() : bool
    {
        return isset($this->tokens[$this->cursor]);
    }

    public function rewind() : void
    {
        $this->cursor = 0;
    }
}
