<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use FatCode\OpenApi\Analyzer\Parser\ClassParser;
use FatCode\OpenApi\Analyzer\Parser\FunctionParser;
use FatCode\OpenApi\Exception\ProjectAnalyzerException;
use FatCode\OpenApi\File\PhpFile;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use SplFileInfo;

/**
 * Class ProjectAnalyser
 * @package FatCode\OpenApi
 */
class ProjectAnalyzer
{
    private $directory;
    private $fileAnalyzer;

    public function __construct(string $directory, FileAnalyzer $fileAnalyzer = null)
    {
        if (!is_dir($directory)) {
            throw ProjectAnalyzerException::forInvalidDirectory($directory);
        }

        $this->directory = new RecursiveDirectoryIterator($directory);
        $this->fileAnalyzer = $fileAnalyzer ?? new FileAnalyzer(
            new ClassParser(),
            new FunctionParser()
        );
    }

    public function analyze() : void
    {
        $allFiles = new RecursiveIteratorIterator($this->directory);
        $phpFiles = new RegexIterator($allFiles, '/.*\.php$/i');
        /** @var SplFileInfo $file */
        foreach ($phpFiles as $file) {
            $this->fileAnalyzer->analyze(new PhpFile($file->getRealPath()));
        }
    }
}
