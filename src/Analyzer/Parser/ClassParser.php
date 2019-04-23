<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer\Parser;

use FatCode\OpenApi\File\PhpFile;

use function is_array;

class ClassParser implements PhpFileParser
{
    use NamespaceParser;

    private $currentNamespace = '';

    public function parse(PhpFile $file) : array
    {
        $classes = [];

        foreach ($file as $index => $token) {
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                $this->currentNamespace = $this->parseNamespaceAt($file);
            }

            if (!is_array($token) || $token[0] !== T_CLASS) {
                continue;
            }

            $classes[] = $this->currentNamespace . '\\' . $this->parseClassAt($file);
        }

        return $classes;
    }

    private function parseClassAt(PhpFile $file) : string
    {
        $file->seekToken(T_STRING);
        $className = $file->current()[1];
        $file->seekStartOfScope();
        $file->skipScope();

        return $className;
    }
}
