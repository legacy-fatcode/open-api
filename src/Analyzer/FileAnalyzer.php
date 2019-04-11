<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use FatCode\OpenApi\Analyzer\Parser\ClassParser;
use FatCode\OpenApi\Analyzer\Parser\FunctionParser;
use FatCode\OpenApi\Analyzer\Parser\NamespaceParser;
use FatCode\OpenApi\File\PhpFile;

class FileAnalyzer
{
    private $namespaceParser;

    private $classParser;

    private $functionParser;

    public function __construct(NamespaceParser $namespaceParser, ClassParser $classParser, FunctionParser $functionParser)
    {
        $this->namespaceParser = $namespaceParser;
        $this->classParser = $classParser;
        $this->functionParser = $functionParser;
    }

    public function analyze(PhpFile $file) : PhpFileInfo
    {
        return new PhpFileInfo(
            $file,
            $this->namespaceParser->parse($file),
            $this->classParser->parse($file),
            $this->functionParser->parse($file)
        );
    }
}
