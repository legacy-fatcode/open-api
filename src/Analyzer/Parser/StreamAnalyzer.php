<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer\Parser;

use FatCode\OpenApi\Analyzer\PhpStream;

interface StreamAnalyzer
{
    /**
     * @param PhpStream $stream
     * @return Symbol[]
     */
    public function analyze(PhpStream $stream) : array;
}
