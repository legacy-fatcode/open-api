<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use function array_merge;
use FatCode\OpenApi\Analyzer\Parser\ClassParser;
use FatCode\OpenApi\Analyzer\Parser\FunctionParser;
use FatCode\OpenApi\Analyzer\Parser\StreamAnalyzer;
use FatCode\OpenApi\Exception\ProjectAnalyzerException;
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
    /**
     * @var string
     */
    private $directory;
    /**
     * @var StreamAnalyzer
     */
    private $analyzers;

    public function __construct(
        string $directory
    ) {
        if (!is_dir($directory)) {
            throw ProjectAnalyzerException::forInvalidDirectory($directory);
        }

        $this->directory = new RecursiveDirectoryIterator($directory);
        $this->analyzers = [
            'classes' => new ClassParser(),
            'functions' => new FunctionParser(),
        ];
    }

    public function analyze() : array
    {
        $allFiles = new RecursiveIteratorIterator($this->directory);
        $phpFiles = new RegexIterator($allFiles, '/.*\.php$/i');
        $result = [];
        /** @var SplFileInfo $file */
        foreach ($phpFiles as $file) {
            /** @var StreamAnalyzer $analyzer */
            foreach ($this->analyzers as $analyzer) {
                $result = array_merge(
                    $result ?? [],
                    $analyzer->analyze(PhpStream::fromFile($file->getRealPath()))
                );
            }
        }

        return $result;
    }
}
