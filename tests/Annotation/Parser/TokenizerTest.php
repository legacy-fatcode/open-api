<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Annotation\Parser;

use Igni\OpenApi\Annotation\Parser\Token;
use Igni\OpenApi\Annotation\Parser\Tokenizer;
use PHPUnit\Framework\TestCase;

final class TokenizerTest extends TestCase
{
    /**
     * @param string $stream
     * @param array $expected
     * @dataProvider provideTokens
     */
    public function testTokenization(string $stream, array $expected) : void
    {
        $tokenizer = new Tokenizer($stream);
        $tokens = $tokenizer->tokenize();
        self::assertCount(count($expected), $tokens);

        $i = 0;
        foreach ($expected as $criteria) {
            self::assertSame($criteria['value'], $tokens[$i]->getValue());
            self::assertSame($criteria['type'], $tokens[$i]->getType());
            $i++;
        }
    }

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
        $token = $tokens[0];

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
        $token = $tokens[0];

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
        $token = $tokens[0];

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
        $token = $tokens[0];

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
        $token = $tokens[0];

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

    public function provideTokens(): array
    {
        return [
            [
                '(key4 = "aaa", key5=[1, 2])',
                [
                    [
                        'value' => '(',
                        'type' => Token::T_OPEN_PARENTHESIS,
                    ],
                    [
                        'value' => 'key4',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => '=',
                        'type' => Token::T_EQUALS,
                    ],
                    [
                        'value' => 'aaa',
                        'type' => Token::T_STRING,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => 'key5',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => '=',
                        'type' => Token::T_EQUALS,
                    ],
                    [
                        'value' => '[',
                        'type' => Token::T_OPEN_BRACKET,
                    ],
                    [
                        'value' => 1,
                        'type' => Token::T_INTEGER,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => 2,
                        'type' => Token::T_INTEGER,
                    ],
                    [
                        'value' => ']',
                        'type' => Token::T_CLOSE_BRACKET,
                    ],
                    [
                        'value' => ')',
                        'type' => Token::T_CLOSE_PARENTHESIS,
                    ],
                ]
            ],
            [
                'Identifier::class',
                [
                   [
                       'value' => 'Identifier',
                       'type' => Token::T_IDENTIFIER,
                   ],
                   [
                       'value' => ':',
                       'type' => Token::T_COLON,
                   ],
                   [
                       'value' => ':',
                       'type' => Token::T_COLON,
                   ],
                   [
                       'value' => 'class',
                       'type' => Token::T_IDENTIFIER,
                   ],
                ]
            ],
            [
                '@Identifier()',
                [
                    [
                        'value' => '@',
                        'type' => Token::T_AT,
                    ],
                    [
                        'value' => 'Identifier',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => '(',
                        'type' => Token::T_OPEN_PARENTHESIS,
                    ],
                    [
                        'value' => ')',
                        'type' => Token::T_CLOSE_PARENTHESIS,
                    ],
                ]
            ],
            [
                '@Fully\Qualified\Namespace()',
                [
                    [
                        'value' => '@',
                        'type' => Token::T_AT,
                    ],
                    [
                        'value' => 'Fully',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => '\\',
                        'type' => Token::T_NAMESPACE_SEPARATOR,
                    ],
                    [
                        'value' => 'Qualified',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => '\\',
                        'type' => Token::T_NAMESPACE_SEPARATOR,
                    ],
                    [
                        'value' => 'Namespace',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => '(',
                        'type' => Token::T_OPEN_PARENTHESIS,
                    ],
                    [
                        'value' => ')',
                        'type' => Token::T_CLOSE_PARENTHESIS,
                    ],
                ],
            ],
            [
                "@Namespace()\n@Namespace2()",
                [
                    [
                        'value' => '@',
                        'type' => Token::T_AT,
                    ],
                    [
                        'value' => 'Namespace',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => '(',
                        'type' => Token::T_OPEN_PARENTHESIS,
                    ],
                    [
                        'value' => ')',
                        'type' => Token::T_CLOSE_PARENTHESIS,
                    ],
                    [
                        'value' => '@',
                        'type' => Token::T_AT,
                    ],
                    [
                        'value' => 'Namespace2',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => '(',
                        'type' => Token::T_OPEN_PARENTHESIS,
                    ],
                    [
                        'value' => ')',
                        'type' => Token::T_CLOSE_PARENTHESIS,
                    ],
                ],
            ],
            [
                "@Namespace(12, true, false, 34.12)",
                [
                    [
                        'value' => '@',
                        'type' => Token::T_AT,
                    ],
                    [
                        'value' => 'Namespace',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => '(',
                        'type' => Token::T_OPEN_PARENTHESIS,
                    ],
                    [
                        'value' => 12,
                        'type' => Token::T_INTEGER,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => true,
                        'type' => Token::T_TRUE,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => false,
                        'type' => Token::T_FALSE,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => 34.12,
                        'type' => Token::T_FLOAT,
                    ],
                    [
                        'value' => ')',
                        'type' => Token::T_CLOSE_PARENTHESIS,
                    ],
                ],
            ],
            [
                "[20, 30.21, @Annotation, false]",
                [
                    [
                        'value' => '[',
                        'type' => Token::T_OPEN_BRACKET,
                    ],
                    [
                        'value' => 20,
                        'type' => Token::T_INTEGER,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => 30.21,
                        'type' => Token::T_FLOAT,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => '@',
                        'type' => Token::T_AT,
                    ],
                    [
                        'value' => 'Annotation',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => false,
                        'type' => Token::T_FALSE,
                    ],
                    [
                        'value' => ']',
                        'type' => Token::T_CLOSE_BRACKET,
                    ],
                ],
            ],
            [
                "@Annotation(null, 34.12, [true, false])",
                [
                    [
                        'value' => '@',
                        'type' => Token::T_AT,
                    ],
                    [
                        'value' => 'Annotation',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => '(',
                        'type' => Token::T_OPEN_PARENTHESIS,
                    ],
                    [
                        'value' => null,
                        'type' => Token::T_NULL,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => 34.12,
                        'type' => Token::T_FLOAT,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => '[',
                        'type' => Token::T_OPEN_BRACKET
                    ],
                    [
                        'value' => true,
                        'type' => Token::T_TRUE,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => false,
                        'type' => Token::T_FALSE,
                    ],
                    [
                        'value' => ']',
                        'type' => Token::T_CLOSE_BRACKET,
                    ],
                    [
                        'value' => ')',
                        'type' => Token::T_CLOSE_PARENTHESIS,
                    ],
                ],
            ],
            [
                "@Annotation\n * \n * [true, false]",
                [
                    [
                        'value' => '@',
                        'type' => Token::T_AT,
                    ],
                    [
                        'value' => 'Annotation',
                        'type' => Token::T_IDENTIFIER,
                    ],
                    [
                        'value' => '*',
                        'type' => Token::T_ASTERISK,
                    ],
                    [
                        'value' => '*',
                        'type' => Token::T_ASTERISK,
                    ],
                    [
                        'value' => '[',
                        'type' => Token::T_OPEN_BRACKET
                    ],
                    [
                        'value' => true,
                        'type' => Token::T_TRUE,
                    ],
                    [
                        'value' => ',',
                        'type' => Token::T_COMMA,
                    ],
                    [
                        'value' => false,
                        'type' => Token::T_FALSE,
                    ],
                    [
                        'value' => ']',
                        'type' => Token::T_CLOSE_BRACKET,
                    ],
                ],
            ],
        ];
    }
}
