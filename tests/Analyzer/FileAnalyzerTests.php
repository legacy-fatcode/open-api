<?php declare(strict_types=1);

namespace FatCode\Tests\OpenApi\Analyzer;

use FatCode\OpenApi\Analyzer\FileAnalyzer;
use FatCode\OpenApi\File\PhpFile;
use PHPUnit\Framework\TestCase;

class FileAnalyzerTests extends TestCase
{
    public function testAnalyze() : void
    {
        (new FileAnalyzer())->analyze(
            new PhpFile(__DIR__ . '/../../examples/hello_world/hello_world.php')
        );
    }
}
