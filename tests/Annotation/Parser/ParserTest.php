<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Annotation\Parser;

use Igni\OpenApi\Annotation\Parser\DocBlock;
use Igni\OpenApi\Annotation\Parser\Parser;
use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
    public function testParseAnnotation() : void
    {
        $parser = new Parser();
        $annotations = $parser->parse(new DocBlock('@Annotation'));

    }
}
