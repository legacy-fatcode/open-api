<?php declare(strict_types=1);

namespace FatCode\Tests\OpenApi\Analyzer;

use const EXAMPLES_DIR;
use FatCode\OpenApi\Analyzer\FileAnalyzer;
use FatCode\OpenApi\File\PhpFile;
use PHPUnit\Framework\TestCase;

class FileAnalyzerTests extends TestCase
{
    public function testAnalyze() : void
    {
        $analyzer = new FileAnalyzer();
        $fileInfo = $analyzer->analyze(new PhpFile(EXAMPLES_DIR . '/hello_world/hello_world.php'));

        $a = 1;

    }
}
