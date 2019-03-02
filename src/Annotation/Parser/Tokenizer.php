<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

use Igni\OpenApi\Exception\TokenizerException;
use Iterator;

class Tokenizer implements Iterator
{
    private const S_NONE = -1;
    private const S_NEUTRAL = 0;
    private const S_STRING = 1;
    private const S_INTEGER = 2;
    private const S_FLOAT = 3;
    private const S_IDENTIFIER = 4;
    private const S_DOCBLOCK = 5;
    private const S_END = 100;

    private $stream;
    private $streamLength;
    private $state = self::S_NONE;
    private $cursor = 0;

    /** @var Token[] */
    private $tokens = [];
    private $iteratorIndex = 0;
    private $iteratorLength = 0;

    public function __construct(string $docBlockComment)
    {
        $this->stream = $docBlockComment;
        $this->streamLength = mb_strlen($this->stream);
    }

    /**
     * @return Token[]
     */
    public function tokenize() : array
    {
        $this->state = self::S_NEUTRAL;
        $line = 0;
        $buffer = '';

        while ($this->cursor < $this->streamLength) {
            $token = null;
            $char = $this->stream[$this->cursor];
            $buffer .= $char;

            $prevChar = null;
            if ($this->cursor - 1 >= 0) {
                $prevChar = $this->stream[$this->cursor - 1];
            }

            $nextChar = null;
            if ($this->cursor + 1 < $this->streamLength) {
                $nextChar = $this->stream[$this->cursor + 1];
            }

            switch (true) {
                case $this->cursor === 0 && $char === '/' && $nextChar === '*':
                    $this->tokens[] = new Token($this->cursor, Token::T_DOCBLOCK_START, '/*');
                    $this->cursor++;
                    $buffer = '';
                    break;

                case $char === '*' && $this->state === self::S_NEUTRAL:
                    if ($nextChar == '/') {
                        $this->tokens[] = new Token($this->cursor, Token::T_DOCBLOCK_END, '*/');
                        $buffer = '';
                        break 2;
                    }
                    $this->tokens[] = new Token($this->cursor, Token::T_ASTERISK, $char);
                    $buffer = '';
                    break;

                case $char === "\n" && $this->state === self::S_DOCBLOCK:
                    $this->tokens[] = new Token($this->cursor - strlen($buffer), Token::T_DOC, $buffer);
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    $line++;
                    break;

                case $char === "\n" && $this->state === self::S_IDENTIFIER:
                    $this->addIdentifier(substr($buffer, 0, -1));
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    $line++;
                    break;

                case $char === "\n":
                    $line++;
                    break;

                case $char === '"' && $this->state === self::S_NEUTRAL:
                    $this->state = self::S_STRING;
                    $buffer = '';
                    break;

                case $char === '"' && $this->state === self::S_STRING:
                    if ($prevChar === '\\') {
                        break;
                    }
                    $this->tokens[] = new Token($this->cursor - strlen($buffer) + 1, Token::T_STRING, stripslashes(substr($buffer, 0, -1)));
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    break;

                case $char === ':' && $this->state !== self::S_STRING:
                    if ($this->state === self::S_IDENTIFIER) {
                        $this->tokens[] = new Token(
                            $this->cursor - strlen($buffer) + 1,
                            Token::T_IDENTIFIER,
                            substr($buffer, 0, -1)
                        );
                        $this->state = self::S_NEUTRAL;
                    }

                    $this->tokens[] = new Token($this->cursor, Token::T_COLON, $char);
                    $buffer = '';
                    break;

                // When dots appears on integer it becomes double
                case $char === '.' && $this->state === self::S_INTEGER:
                    $this->state = self::S_FLOAT;
                    break;

                // When dot appears on number state
                case $char === '.' && $this->state === self::S_FLOAT:
                    $this->tokens[] = new Token(
                        $this->cursor - strlen($buffer) + 1,
                        Token::T_FLOAT,
                        (float) substr($buffer, 0, -1)
                    );
                    $this->tokens[] = new Token($this->cursor, Token::T_DOT, $char);
                    $this->state = self::S_NEUTRAL;
                    break;

                case $char === '.' && $this->state === self::S_IDENTIFIER:
                    $this->addIdentifier(substr($buffer, 0, -1));
                    $this->tokens[] = new Token($this->cursor, Token::T_DOT, $char);
                    $this->state = self::S_NEUTRAL;
                    break;

                case $char === ',' && in_array($this->state, [self::S_DOCBLOCK, self::S_STRING], true):
                    // Ignore commas in docblock empty state and string
                    break;

                case $char === ',' && $this->state === self::S_NEUTRAL:
                    $this->tokens[] = new Token($this->cursor, Token::T_COMMA, $char);
                    $buffer = '';
                    break;

                case $char === ',':
                    $this->handleInterrupt(substr($buffer, 0, -1));
                    $this->tokens[] = new Token($this->cursor, Token::T_COMMA, $char);
                    $buffer = '';
                    break;

                case ctype_space($char):
                    if (in_array($this->state, [self::S_NEUTRAL, self::S_DOCBLOCK, self::S_STRING], true)) {
                        break; // Ignore whitespaces in docblock empty state and string
                    }

                    $this->handleInterrupt(substr($buffer, 0, -1));
                    break;

                case ctype_digit($char) && $this->state === self::S_NEUTRAL:
                    $this->state = self::S_INTEGER;
                    break;

                case ctype_alpha($char) && $this->state === self::S_NEUTRAL:
                    $buffer = $char;
                    $this->state = self::S_IDENTIFIER;
                    break;

                case $char === '@' && $this->state === self::S_NEUTRAL:
                    $this->tokens[] = new Token($this->cursor, Token::T_AT, $char);
                    $buffer = '';
                    break;

                case $char === '@' && in_array($this->state, [self::S_IDENTIFIER, self::S_INTEGER, self::S_FLOAT]):
                    $this->state = self::S_DOCBLOCK;
                    break;

                case $char === '(' && $this->state === self::S_NEUTRAL:
                    $this->tokens[] = new Token($this->cursor, Token::T_OPEN_PARENTHESIS, $char);
                    $buffer = '';
                    break;

                case $char === '(' && $this->state === self::S_IDENTIFIER:
                    $this->addIdentifier(substr($buffer, 0, -1));
                    $this->tokens[] = new Token($this->cursor, Token::T_OPEN_PARENTHESIS, $char);
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    break;

                case $char === '(' && in_array($this->state, [self::S_FLOAT, self::S_INTEGER]):
                    $this->state = self::S_DOCBLOCK;
                    break;

                case $char === ')' && $this->state === self::S_NEUTRAL:
                    $this->tokens[] = new Token($this->cursor, Token::T_CLOSE_PARENTHESIS, $char);
                    $buffer = '';
                    break;

                case $char === ')' && $this->state === self::S_IDENTIFIER:
                    $this->addIdentifier(substr($buffer, 0, -1));
                    $this->tokens[] = new Token($this->cursor, Token::T_CLOSE_PARENTHESIS, $char);
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    break;

                case $char === ')' && $this->state === self::S_FLOAT:
                    $this->tokens[] = new Token(
                        $this->cursor - strlen($buffer) + 1,
                        Token::T_FLOAT,
                        (float) substr($buffer, 0, -1)
                    );
                    $this->tokens[] = new Token($this->cursor, Token::T_CLOSE_PARENTHESIS, $char);
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    break;

                case $char === ')' && $this->state === self::S_INTEGER:
                    $this->tokens[] = new Token(
                        $this->cursor - strlen($buffer) + 1,
                        Token::T_INTEGER,
                        (int) substr($buffer, 0, -1)
                    );
                    $this->tokens[] = new Token($this->cursor, Token::T_CLOSE_PARENTHESIS, $char);
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    break;

                case $char === '\\' && $this->state === self::S_IDENTIFIER:
                    $this->tokens[] = new Token(
                        $this->cursor - strlen($buffer) + 1,
                        Token::T_IDENTIFIER,
                        substr($buffer, 0, -1)
                    );
                    $this->tokens[] = new Token($this->cursor, Token::T_NAMESPACE_SEPARATOR, $char);
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    break;

                case $char === '\\' && in_array($this->state, [self::S_FLOAT, self::S_INTEGER], true):
                    $this->state = self::S_DOCBLOCK;
                    break;

                case $char === '[' && $this->state === self::S_NEUTRAL:
                    $this->tokens[] = new Token($this->cursor, Token::T_OPEN_BRACKET, $char);
                    $buffer = '';
                    break;

                case $char === '[' && in_array($this->state, [self::S_FLOAT, self::S_INTEGER, self::S_IDENTIFIER]):
                    $this->state = self::S_DOCBLOCK;
                    break;

                case $char === ']' && $this->state === self::S_NEUTRAL:
                    $this->tokens[] = new Token($this->cursor, Token::T_CLOSE_BRACKET, $char);
                    $buffer = '';
                    break;

                case $char === ']' && $this->state === self::S_IDENTIFIER:
                    $this->addIdentifier(substr($buffer, 0, -1));
                    $this->tokens[] = new Token($this->cursor, Token::T_CLOSE_BRACKET, $char);
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    break;

                case $char === ']':
                    $this->handleInterrupt(substr($buffer, 0, -1));
                    $this->tokens[] = new Token($this->cursor, Token::T_CLOSE_BRACKET, $char);
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    break;

                case $char === '=' && $this->state === self::S_NEUTRAL:
                    $this->tokens[] = new Token($this->cursor, Token::T_EQUALS, $char);
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    break;

                case $char === '=' && $this->state === self::S_IDENTIFIER:
                    $this->addIdentifier(substr($buffer, 0, -1));
                    $this->tokens[] = new Token($this->cursor, Token::T_EQUALS, $char);
                    $this->state = self::S_NEUTRAL;
                    $buffer = '';
                    break;

                case $this->state === self::S_IDENTIFIER && (!ctype_alnum($char) && $char !== '_'):
                    $this->state = self::S_DOCBLOCK;
                    break;
            }
            $this->cursor++;
        }

        $this->handleInterrupt($buffer);
        $this->state = self::S_END;

        $this->iteratorLength = count($this->tokens);
        return $this->tokens;
    }

    private function handleInterrupt(string $buffer) : void
    {
        $index = $this->cursor - strlen($buffer) + 1;
        switch ($this->state) {
            case self::S_INTEGER:
                $this->tokens[] = new Token(
                    $index,
                    Token::T_INTEGER,
                    (int) $buffer
                );
                break;

            case self::S_FLOAT:
                $this->tokens[] = new Token(
                    $index,
                    Token::T_FLOAT,
                    (float) $buffer
                );
                break;

            case self::S_IDENTIFIER:
                $this->addIdentifier($buffer);
                break;

            case self::S_DOCBLOCK:
                $this->tokens[] = new Token(
                    $index,
                    Token::T_DOC,
                    $buffer
                );
                break;
        }

        $this->state = self::S_NEUTRAL;
    }

    private function addIdentifier(string $buffer) : void
    {
        $index = $this->cursor - strlen($buffer) + 1;
        if (strtolower($buffer) === 'true') {
            $this->tokens[] = new Token(
                $index,
                Token::T_TRUE,
                true
            );
        } elseif (strtolower($buffer) === 'false') {
            $this->tokens[] = new Token(
                $index,
                Token::T_FALSE,
                false
            );
        } elseif (strtolower($buffer) === 'null') {
            $this->tokens[] = new Token(
                $index,
                Token::T_NULL,
                null
            );
        }  else {
            $this->tokens[] = new Token(
                $index,
                Token::T_IDENTIFIER,
                $buffer
            );
        }
    }

    public function seek(int $type) : bool
    {
        for ($this->iteratorIndex; $this->iteratorIndex < $this->iteratorLength; $this->iteratorIndex++) {
            if ($this->current()->getType() === $type) {
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
        return $this->tokens[$this->iteratorLength - 1];
    }

    public function current() : Token
    {
        return $this->tokens[$this->iteratorIndex];
    }

    public function prev() : void
    {
        $this->iteratorIndex--;
        if ($this->iteratorIndex < 0) {
            $this->iteratorIndex = 0;
        }
    }

    public function next() : void
    {
        $this->iteratorIndex++;
    }

    public function key() : int
    {
        return $this->iteratorIndex;
    }

    public function valid() : bool
    {
        return isset($this->tokens[$this->iteratorIndex]);
    }

    public function rewind() : void
    {
        if ($this->state === self::S_NONE) {
            $this->tokenize();
        }

        if ($this->state !== self::S_END) {
            throw TokenizerException::forUnexpectedRewindCall();
        }

        $this->iteratorIndex = 0;
    }
}
