<?php declare(strict_types=1);

namespace FatCode\Tests\OpenApi\Analyzer;

use FatCode\OpenApi\Analyzer\Project;
use FatCode\OpenApi\Analyzer\ProjectFactory;
use PHPUnit\Framework\TestCase;

class ProjectAnalyzerTests extends TestCase
{
    public function testAnalyze() : void
    {
        $projectAnalyzer = new ProjectFactory(__DIR__ . '/../../examples/hello_world');
        $project = $projectAnalyzer->create();

        self::assertInstanceOf(Project::class, $project);
        self::assertCount(3, $project->getSymbols());
    }
}
