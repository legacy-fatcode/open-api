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

    /**
     * @param string $stream
     * @param string $expected
     * @dataProvider provideIdentifiers
     */
    public function testTokenizeIdentifier(string $stream, string $expected) : void
    {
        $tokenizer = new Tokenizer($stream);
        $tokens = $tokenizer->tokenize();

        $token = $tokenizer->first();

        self::assertCount(1, $tokens);
        self::assertSame(Token::T_IDENTIFIER, $token->getType());
        self::assertSame($expected, $token->getValue());
    }

    /**
     * @param string $stream
     * @param int $expected
     * @dataProvider provideIntegers
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
     * @param bool $bool
     * @dataProvider provideBooleans
     */
    public function testTokenizeBool(string $stream, bool $bool) : void
    {
        $tokenizer = new Tokenizer($stream);
        $tokens = $tokenizer->tokenize();
        $token = $tokenizer->first();

        self::assertCount(1, $tokens);
        if ($bool) {
            self::assertSame(Token::T_TRUE, $token->getType());
            self::assertTrue($token->getValue());
        } else {
            self::assertSame(Token::T_FALSE, $token->getType());
            self::assertFalse($token->getValue());
        }

    }

    /**
     * @param string $stream
     * @param float $expected
     * @dataProvider provideFloats
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

    public function provideBooleans() : array
    {
        return [
            ['true', true],
            ['false', false],
            [' true', true],
            [' false ', false],
            ['true ', true]
        ];
    }

    public function provideIntegers() : array
    {
        return [
            ['12', 12],
            [' 12', 12],
            [' 12 ', 12],
            ['12 ', 12]
        ];
    }

    public function provideFloats() : array
    {
        return [
            ['12.21', 12.21],
            [' 12.21', 12.21],
            [' 12.21 ', 12.21],
            ['12.22 ', 12.22]
        ];
    }

    public function provideIdentifiers() : array
    {
        return [
            ['Identifier12 ', 'Identifier12'],
            [' Identifier12 ', 'Identifier12'],
            [' Identifier12', 'Identifier12'],
        ];
    }
}
