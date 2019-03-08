<?php declare(strict_types=1);

namespace IgniTest\OpenApi\Annotation\Parser;

use Igni\OpenApi\Annotation\Parser\ReflectorImports;
use IgniTest\OpenApi\Fixtures\PetShopApplication;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class ReflectorImportsTest extends TestCase
{
    public function testParseAnnotation() : void
    {
       $imports = new ReflectorImports(new ReflectionClass(PetShopApplication::class));
       $a = $imports->getImports();
    }
}
