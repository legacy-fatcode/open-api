<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use IgniTest\OpenApi\Fixtures\PetSchema;

require_once '../vendor/autoload.php';

$a = microtime(true);

$lexer = new \Doctrine\Common\Annotations\DocLexer();
$reflection = new ReflectionClass(PetSchema::class);
$lexer->setInput($reflection->getDocComment());

while($lexer->moveNext()) {
//    print_r($lexer->token);
}
echo microtime(true) - $a;