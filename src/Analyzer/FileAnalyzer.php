<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use FatCode\OpenApi\Exception\ProjectAnalyzerException;
use Throwable;

class FileAnalyzer
{
    private $fileName;

    public function __construct(string $fileName)
    {
        if (!is_file($fileName) || !is_readable($fileName)) {
            throw ProjectAnalyzerException::forUnreadableFile($fileName);
        }
        try {
            require_once $fileName;
        } catch (Throwable $throwable) {
            throw ProjectAnalyzerException::forInvalidFile($fileName);
        }

        $this->fileName = $fileName;
    }

    public function analyze() : void
    {
        $contents = file_get_contents($this->fileName);
        $tokens = token_get_all($contents);
        foreach ($tokens as $token) {

        }
    }
}
