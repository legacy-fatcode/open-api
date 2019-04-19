<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer\Parser;

use FatCode\OpenApi\File\PhpFile;

interface PhpFileParser
{
    public function parse(PhpFile $file) : array;
}