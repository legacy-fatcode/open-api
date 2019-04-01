<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use function App\HelloWorld\dupa;
use FatCode\OpenApi\Exception\ProjectAnalyzerException;
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
            $fileAnalyzer = new FileAnalyzer($file->getRealPath());
            $fileAnalyzer->analyze();
        }
    }

    public function readFromFile(string $filename) : void
    {
    }
}
