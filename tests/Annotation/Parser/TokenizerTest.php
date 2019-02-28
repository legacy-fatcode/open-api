<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Annotation\Parser;

use Igni\OpenApi\Annotation\Parser\Token;
use Igni\OpenApi\Annotation\Parser\Tokenizer;
use PHPUnit\Framework\TestCase;

final class TokenizerTest extends TestCase
{
    public function testTokenizeEmptyDocBlock() : void
    {
        $tokenizer = new Tokenizer('/***/');
        $tokens = $tokenizer->tokenize();
        self::assertCount(3, $tokens);
        self::assertSame(Token::T_DOCBLOCK_START, $tokenizer->first()->getType());
        self::assertSame(Token::T_DOCBLOCK_END, $tokenizer->last()->getType());
    }

    public function testTokenizeString() : void
    {
        $tokenizer = new Tokenizer('"Test string with escaped\" and unescaped"');
        $tokens = $tokenizer->tokenize();
        $token = $tokenizer->first();

        self::assertCount(1, $tokens);
        self::assertSame(Token::T_STRING, $token->getType());
        self::assertSame('Test string with escaped" and unescaped', $token->getValue());
    }

    public function testTokenizeIdentifier() : void
    {
        $tokenizer = new Tokenizer('SomeIdentifier12');
        $tokens = $tokenizer->tokenize();
    }

    /**
     * @param string $stream
     * @param int $expected
     * @dataProvider provideIntegerExamples
     */
    public function testTokenizeInteger(string $stream, int $expected) : void
    {
        $tokenizer = new Tokenizer($stream);
        $tokens = $tokenizer->tokenize();
        $token = $tokenizer->first();

        self::assertCount(1, $tokens);
        self::assertSame(Token::T_INTEGER, $token->getType());
        self::assertSame($expected, $token->getValue());
    }

    /**
     * @param string $stream
     * @param float $expected
     * @dataProvider provideFloatExamples
     */
    public function testTokenizeFloats(string $stream, float $expected) : void
    {
        $tokenizer = new Tokenizer($stream);
        $tokens = $tokenizer->tokenize();
        $token = $tokenizer->first();

        self::assertCount(1, $tokens);
        self::assertSame(Token::T_FLOAT, $token->getType());
        self::assertSame($expected, $token->getValue());
    }

    public function provideIntegerExamples() : array
    {
        return [
            ['12', 12],
            [' 12', 12],
            [' 12 ', 12],
            ['12 ', 12]
        ];
    }

    public function provideFloatExamples() : array
    {
        return [
            ['12.21', 12.21],
            [' 12.21', 12.21],
            [' 12.21 ', 12.21],
            ['12.22 ', 12.22]
        ];
    }
}