<?php declare(strict_types=1);

namespace FatCode\Tests\OpenApi\Analyzer;

use FatCode\OpenApi\Analyzer\ProjectAnalyzer;
use PHPUnit\Framework\TestCase;

class ProjectAnalyzerTests extends TestCase
{
    public function testAnalyze() : void
    {
        $projectAnalyzer = new ProjectAnalyzer(__DIR__ . '/../../examples/hello_world');
        $analyze = $projectAnalyzer->analyze();

        self::assertCount(1, $analyze);
    }
}
