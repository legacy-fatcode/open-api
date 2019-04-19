<?php declare(strict_types=1);

namespace FatCode\OpenApi\File;

use InvalidArgumentException;

class FileException extends InvalidArgumentException
{
    public static function notReadable(string $fileName): self
    {
        return new self("Passed file `{$fileName}` could not be opened for read.");
    }

    public static function invalidFile(string $filename) : self
    {
        return new self("Passed file `{$filename}` is not valid php file.");
    }
}