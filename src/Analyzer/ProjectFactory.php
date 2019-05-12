<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use FatCode\OpenApi\Analyzer\Parser\ClassParser;
use FatCode\OpenApi\Analyzer\Parser\FunctionParser;
use FatCode\OpenApi\Analyzer\Parser\StreamAnalyzer;
use FatCode\OpenApi\Exception\ProjectAnalyzerException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use SplFileInfo;

class ProjectFactory
{
    /**
     * @var StreamAnalyzer[]
     */
    private $analyzers;

    public function __construct()
    {
        $this->analyzers = [
            'classes' => new ClassParser(),
            'functions' => new FunctionParser(),
        ];
    }

    public function create(string $dirname) : Project
    {
        if (!is_dir($dirname)) {
            throw ProjectAnalyzerException::forInvalidDirectory($dirname);
        }
        $directory = new RecursiveDirectoryIterator($dirname);
        $allFiles = new RecursiveIteratorIterator($directory);
        $phpFiles = new RegexIterator($allFiles, '/.*\.php$/i');
        $project = new Project();
        /** @var SplFileInfo $file */
        foreach ($phpFiles as $file) {
            foreach ($this->analyzers as $analyzer) {
                $project->addSymbol(...$analyzer->analyze(PhpStream::fromFile($file->getRealPath())));
            }
        }

        return $project;
    }
}
