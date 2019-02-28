<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

class Tokenizer
{
    private $current;
    private $stream;
    private $cursor = 0;
    private $index = 0;
    private $length;
    private $tokens;

    public function __construct(string $docBlockComment)
    {
        $this->stream = $docBlockComment;
        $this->length = mb_strlen($this->stream);
        $this->current = new Token(0, Token::T_NONE, '');
    }

    public function tokenize() : void
    {
        $line = 0;
        $lineBuffer = '';
        while ($this->cursor < $this->length) {
            $char = $this->stream[$this->cursor++];

            $lineBuffer .= $char;

            $prevChar = null;
            if ($this->cursor - 1 >= 0) {
                $prevChar = $this->stream[$this->cursor - 1];
            }

            $nextChar = null;
            if ($this->cursor + 1 < $this->length) {
                $nextChar = $this->stream[$this->cursor + 1];
            }

            switch (true) {
                case $char === '/' && $this->current()->getType() === Token::T_NONE && $nextChar === '*':
                    $token = new Token($this->cursor, Token::T_DOCBLOCK_START, '/*');
                    $this->cursor ++;
                    break;
                case $char === '*' && preg_match('//?\s*\**\s*/', $lineBuffer):
                    $token = new Token($this->cursor, Token::T_ASTERISK, $char);
                    break;
                case $char === "\n":
                    $line++;
                    $lineBuffer = '';
                    break;
                case $char === '{':
                    $token = new Token($this->cursor, Token::T_ASTERISK, $char);
                    break;
            }

            $this->tokens[] = $token;
        }
    }

    /**
     * Returns current token
     * @return Token
     */
    public function current() : Token
    {
        return $this->current;
    }

    public function next() : Token
    {
        $this->index++;
        return $this->tokens[$this->index];
    }

    public function seek(int $type) : Token
    {
        while ($this->current()->getType() !== $type) {
            $this->next();
        }

        return $this->current();
    }

    public function previous() : Token
    {
        if ($this->index === 0) {

        }
    }

    public function reset() : void
    {
        $this->index = 0;
    }
}
