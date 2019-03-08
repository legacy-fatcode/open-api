<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Annotation\Parser;

use Igni\OpenApi\Annotation\Parser\Context;
use Igni\OpenApi\Annotation\Parser\Parser;
use PHPUnit\Framework\TestCase;
use IgniTest\OpenApi\Fixtures\PetShopApplication;

use function IgniTest\OpenApi\Fixtures\getPet;

final class ParserTest extends TestCase
{
    public function testParseAnnotation() : void
    {
        $reflection = new \ReflectionMethod(PetShopApplication::class, 'getPet');
        $parser = new Parser();
        $annotations = $parser->parse($reflection->getDocComment(), Context::fromReflectionMethod($reflection));
        $a = 1;
    }
}
