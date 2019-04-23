<?php declare(strict_types=1);

namespace FatCode\OpenApi\Exception;

use FatCode\OpenApi\Exception\RuntimeException as FatCodeRuntimeException;
use RuntimeException;

class ProjectAnalyzerException extends RuntimeException implements FatCodeRuntimeException
{
    public static function forInvalidDirectory(string $directory) : self
    {
        return new self("Passed directory `{$directory}` is not a valid directory name.");
    }

    public static function forUnreadableFile(string $filename) : self
    {
        return new self("Passed file `{$filename}` could not be opened for read.");
    }

    public static function forInvalidFile(string $filename) : self
    {
        return new self("Passed file `{$filename}` is not valid php file.");
    }

    public static function forInvalidNamespace() : self
    {
        return new self('Could not parse namespace.');
    }
}
