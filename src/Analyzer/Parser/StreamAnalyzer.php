<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer\Parser;

use FatCode\OpenApi\Analyzer\PhpStream;

interface StreamAnalyzer
{
    public function analyze(PhpStream $stream) : array;
}
