<?php declare(strict_types=1);

namespace FatCode\OpenApi\File;

use Throwable;

class PhpFile
{
    private $name;

    public function __construct(string $name)
    {
        self::validateFile($name);
        $this->name = $name;
    }

    public function getTokens() : array
    {
        return token_get_all(file_get_contents($this->name));
    }

    public function getTokenAt(int $cursor)
    {
        return $this->getTokens()[$cursor];
    }

    public function countTokens(): int
    {
        return count($this->getTokens());
    }

    public function __toString() : string
    {
        return $this->name;
    }

    private static function validateFile(string $name) : void
    {
        if (!is_file($name) || !is_readable($name)) {
            throw FileException::notReadable($name);
        }

        try {
            require_once $name;
        } catch (Throwable $throwable) {
            throw FileException::invalidFile($name);
        }
    }
}