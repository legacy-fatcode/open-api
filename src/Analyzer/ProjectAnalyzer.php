<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use FatCode\OpenApi\Exception\ProjectAnalyzerException;
use FatCode\OpenApi\File\PhpFile;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * Class ProjectAnalyser
 * @package FatCode\OpenApi
 */
class ProjectAnalyzer
{
    private $directory;

    public function __construct(string $directory)
    {
        if (!is_dir($directory)) {
            throw ProjectAnalyzerException::forInvalidDirectory($directory);
        }

        $this->directory = new RecursiveDirectoryIterator($directory);
    }

    public function analyze() : void
    {
        $allFiles = new RecursiveIteratorIterator($this->directory);
        $phpFiles = new RegexIterator($allFiles, '/.*\.php$/i');
        /** @var \SplFileInfo $file */
        foreach ($phpFiles as $file) {
            $fileAnalyzer = new FileAnalyzer();
            $fileAnalyzer->analyze(new PhpFile($file->getRealPath()));
        }
    }

    public function readFromFile(string $filename) : void
    {
    }
}
