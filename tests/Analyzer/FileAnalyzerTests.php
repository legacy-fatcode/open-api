<?php declare(strict_types=1);

namespace FatCode\Tests\OpenApi\Analyzer;

use FatCode\OpenApi\Analyzer\FileAnalyzer;
use FatCode\OpenApi\File\PhpFile;
use PHPUnit\Framework\TestCase;

use const EXAMPLES_DIR;

class FileAnalyzerTests extends TestCase
{
    public function testAnalyze() : void
    {
        $analyzer = new FileAnalyzer();
        $fileInfo = $analyzer->analyze(new PhpFile(EXAMPLES_DIR . '/hello_world/hello_world.php'));

        self::assertSame(['App\HelloWorld\Application', 'App\HelloWorld\Greeter'], $fileInfo->getClasses());
        self::assertSame(['App\HelloWorld\sayHello'], $fileInfo->getFunctions());
    }
}
