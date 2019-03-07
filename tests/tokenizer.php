<?php
require_once '../vendor/autoload.php';

$a = microtime(true);
$reflection = new ReflectionClass(\IgniTest\OpenApi\Fixtures\PetSchema::class);
$tokenizer = new \Igni\OpenApi\Annotation\Parser\Tokenizer($reflection->getDocComment());
echo microtime(true) - $a;