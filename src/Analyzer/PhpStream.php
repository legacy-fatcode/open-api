<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use FatCode\OpenApi\Exception\FileException;
use Iterator;
use Throwable;

use function count;
use function file_get_contents;
use function is_file;
use function is_readable;
use function token_get_all;

class PhpStream implements Iterator
{
    private $cursor;
    private $tokens;
    private $length;
    private $stream;

    private function __construct(string $stream)
    {
        $this->stream = $stream;
        $this->tokens = token_get_all($stream);
        $this->length = count($this->tokens);
    }

    public static function fromFile(string $fileName) : PhpStream
    {
        self::validateFile($fileName);
        return new self(file_get_contents($fileName));
    }

    public function current()
    {
        return $this->tokens[$this->cursor];
    }

    public function prev() : void
    {
        $this->cursor--;
    }

    public function next() : void
    {
        $this->cursor++;
    }

    public function key()
    {
        return $this->cursor;
    }

    public function valid() : bool
    {
        return $this->cursor >= 0 && $this->cursor < $this->length;
    }

    public function rewind() : void
    {
        $this->cursor = 0;
    }

    public function getCursor() : int
    {
        return $this->cursor;
    }

    public function getTokens() : array
    {
        return $this->tokens;
    }

    public function getTokenAt(int $cursor)
    {
        return $this->tokens[$cursor];
    }

    public function seekToken(int $token) : bool
    {
        while ($this->valid()) {
            $current = $this->current();

            if (!is_array($current)) {
                continue;
            }

            if ($current[0] === $token) {
                return true;
            }
            $this->next();
        }

        return false;
    }

    public function seekStartOfScope() : bool
    {
        while ($this->valid()) {
            $current = $this->current();

            if (is_array($current)) {
                $this->next();
                continue;
            }

            if ($current === '{') {
                return true;
            }
            $this->next();
        }

        return false;
    }

    public function skipScope() : bool
    {
        if ($this->current() === '{') {
            $this->next();
        }
        $depth = 1;
        while ($this->valid()) {
            $current = $this->current();

            if (is_array($current)) {
                $this->next();
                continue;
            }

            if ($current === '{') {
                $depth++;
                $this->next();
                continue;
            }

            if ($current === '}') {
                $depth--;
                if ($depth === 0) {
                    return true;
                }
            }
            $this->next();
        }

        return false;
    }

    public function countTokens() : int
    {
        return $this->length;
    }

    public function __toString() : string
    {
        return $this->stream;
    }

    private static function validateFile(string $fileName) : void
    {
        if (!is_file($fileName) || !is_readable($fileName)) {
            throw FileException::notReadable($fileName);
        }

        try {
            require_once $fileName;
        } catch (Throwable $throwable) {
            throw FileException::invalidFile($fileName);
        }
    }
}
