<?php declare(strict_types=1);

namespace FatCode\Tests\OpenApi\Analyzer\Parser;

use FatCode\OpenApi\Analyzer\Parser\FunctionParser;
use FatCode\OpenApi\Analyzer\PhpStream;
use PHPUnit\Framework\TestCase;

final class FunctionParserTest extends TestCase
{
    public function testCanInstantiate() : void
    {
        self::assertInstanceOf(FunctionParser::class, new FunctionParser());
    }

    public function testParse() : void
    {
        $parser = new FunctionParser();
        $info = $parser->analyze(PhpStream::fromFile(__FILE__));

        self::assertCount(2, $info);
        self::assertSame(__NAMESPACE__ . '\test_a', $info[0]);
        self::assertSame(__NAMESPACE__ . '\test_b', $info[1]);
    }
}

function test_a()
{
}

function test_b()
{
}
