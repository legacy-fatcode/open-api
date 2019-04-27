<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use FatCode\OpenApi\Analyzer\Parser\ClassParser;
use FatCode\OpenApi\Analyzer\Parser\FunctionParser;
use FatCode\OpenApi\File\PhpFile;

class FileAnalyzer
{
    private $classParser;

    private $functionParser;

    public function __construct(
        ClassParser $classParser,
        FunctionParser $functionParser
    ) {
        $this->classParser = $classParser;
        $this->functionParser = $functionParser;
    }

    public function analyze(PhpFile $file) : PhpFileInfo
    {
        return new PhpFileInfo(
            $file,
            $this->classParser->parse($file),
            $this->functionParser->parse($file)
        );
    }
}
