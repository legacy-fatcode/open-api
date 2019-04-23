<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer\Parser;

use FatCode\OpenApi\Exception\ProjectAnalyzerException;
use FatCode\OpenApi\File\PhpFile;

use function is_array;

trait NamespaceParser
{
    protected function parseNamespaceAt(PhpFile $file) : string
    {
        $namespace = '';
        while ($file->valid()) {
            $file->next();
            $token = $file->current();

            if (is_array($token) && ($token[0] === T_STRING || $token[0] === T_NS_SEPARATOR)) {
                $namespace .= $token[1];
            }

            if ($token === ';' || $token === '{') { // End of namespace
                return $namespace;
            }
        }

        throw ProjectAnalyzerException::forInvalidNamespace();
    }
}
