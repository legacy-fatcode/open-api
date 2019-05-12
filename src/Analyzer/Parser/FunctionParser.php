<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer\Parser;

use FatCode\OpenApi\Analyzer\PhpStream;

use function is_array;

use const T_FUNCTION;
use const T_NAMESPACE;
use const T_STRING;

class FunctionParser implements StreamAnalyzer
{
    use NamespaceParser;

    private $currentNamespace = '';

    /**
     * @param PhpStream $stream
     * @return Symbol[]
     */
    public function analyze(PhpStream $stream) : array
    {
        $results = [];

        foreach ($stream as $index => $token) {
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                $this->currentNamespace = $this->parseNamespace($stream);
            }

            if (is_array($token) && $token[0] === T_CLASS) {
                $stream->seekStartOfScope();
                $stream->skipScope();
                continue;
            }

            if (!is_array($token) || $token[0] !== T_FUNCTION) {
                continue;
            }

            $results[] = new Symbol(
                $this->currentNamespace . '\\' . $this->parseFunction($stream),
                SymbolType::TYPE_FUNCTION()
            );
        }

        return $results;
    }

    private function parseFunction(PhpStream $file) : string
    {
        $file->seekToken(T_STRING);
        $className = $file->current()[1];
        $file->seekStartOfScope();
        $file->skipScope();

        return $className;
    }
}
