<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Annotation\Parser;

use Igni\OpenApi\Annotation\Parser\Lexer;
use PHPUnit\Framework\TestCase;

final class LexerTests extends TestCase
{
    public function testParseAnnotation() : void
    {
        $lexer = new Lexer('"@Annotation\n * \n * [true, false]"');

        $a = 1;
    }
}
