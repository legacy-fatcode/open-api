<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer\Parser;

use FatCode\OpenApi\File\PhpFile;

class NamespaceParser implements PhpFileParser
{
    public function parse(PhpFile $file) : array
    {
        $namespaces = [];

        for ($cursor = 0; $cursor < $file->countTokens(); $cursor++) {
            $token = $file->getTokenAt($cursor);

            if (!is_array($token) || $token[0] !== T_NAMESPACE) {
                continue;
            }

            $namespaces[] = $this->parseNamespaceAt($cursor, $file);
        }

        return $namespaces;
    }

    private function parseNamespaceAt(int $cursor, PhpFile $file): string
    {
        $namespace = '';

        while ($cursor++ < $file->countTokens()) {
            $token = $file->getTokenAt($cursor);

            if (is_array($token) && ($token[0] === T_STRING || $token[0] === T_NS_SEPARATOR)) {
                $namespace .= $token[1];
            }

            if ($token === ';' || $token === '{') { // End of namespace
                return $namespace;
            }
        }
    }
}
