<?php declare(strict_types=1);

namespace FatCode\Tests\OpenApi\Analyzer\Parser;

use FatCode\OpenApi\Analyzer\Parser\ClassParser;
use FatCode\OpenApi\File\PhpFile;
use PHPUnit\Framework\TestCase;

final class ClassParserTest extends TestCase
{
    public function testCanInstantiate() : void
    {
        self::assertInstanceOf(ClassParser::class, new ClassParser());
    }

    public function testParse() : void
    {
        $parser = new ClassParser();
        $info = $parser->parse(new PhpFile(__FILE__));

        self::assertCount(1, $info);
        self::assertSame(ClassParserTest::class, $info[0]);
    }
}
