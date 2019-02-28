<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

class Tokenizer
{
    private $current;
    private $stream;
    private $cursor = 0;
    private $index = 0;
    private $length;

    public function __construct(string $docBlockComment)
    {
        $this->stream = $docBlockComment;
        $this->length = mb_strlen($this->stream);
        $this->current = new Token(0, Token::T_NONE, '');
    }

    public function tokenize() : void
    {

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
        $char = $this->stream[$this->cursor];
        switch (true) {
            case $char === '/' && $this->current()->getType() === Token::T_NONE:
                break;
        }

        $this->index++;
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