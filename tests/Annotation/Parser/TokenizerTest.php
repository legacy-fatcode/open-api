<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Annotation\Parser;

use Igni\OpenApi\Annotation\Tokenizer;
use PHPUnit\Framework\TestCase;

final class TokenizerTest extends TestCase
{
    public function testRunTokenizerOnEmptyDocBlock() : void
    {
        $tokenizer = new Tokenizer();
        $tokens = $tokenizer->tokenize('/***/');
    }
}