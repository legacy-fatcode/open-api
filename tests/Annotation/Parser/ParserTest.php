<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Annotation\Parser;

use ReflectionClass;
use Igni\OpenApi\Annotation\Parser\Context;
use Igni\OpenApi\Annotation\Parser\Parser;
use IgniTest\OpenApi\Fixtures\PetSchema;
use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
    public function testParseAnnotation() : void
    {
        $reflection = new ReflectionClass(PetSchema::class);
        $parser = new Parser();
        $annotations = $parser->parse($reflection->getDocComment(), Context::fromReflectionClass($reflection));

    }
}
