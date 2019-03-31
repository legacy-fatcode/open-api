<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use FatCode\OpenApi\Exception\ProjectAnalyzerException;
use Throwable;

class FileAnalyzer
{
    private $fileName;
    private $cursor;
    private $currentNamespace;
    private $declaredClasses = [];
    private $declaredMethods = [];
    private $declaredFunctions = [];
    private $declaredTraits = [];
    private $usedNamespaces = [];

    public function __construct(string $fileName)
    {
        if (!is_file($fileName) || !is_readable($fileName)) {
            throw ProjectAnalyzerException::forUnreadableFile($fileName);
        }
        try {
            require_once $fileName;
        } catch (Throwable $throwable) {
            throw ProjectAnalyzerException::forInvalidFile($fileName);
        }

        $this->fileName = $fileName;
    }

    public function analyze() : void
    {
        $contents = file_get_contents($this->fileName);
        $tokens = token_get_all($contents);
        for ($this->cursor = 0; $this->cursor < count($tokens); $this->cursor++) {
            $token = $tokens[$this->cursor];
            if (!is_array($token)) {
                continue;
            }

            switch ($token[0]) {
                case T_NAMESPACE:
                    $this->parseNamespace();
                    break;
                case T_CLASS:
                    $this->parseClass();
                    break;
                case T_FUNCTION:
                    $this->parseFunction();
                    break;
                case T_USE:
                    $this->parseUse();
                    break;
                case T_INTERFACE:
                    $this->parseInterface();
                    break;
                case T_TRAIT:
                    $this->parseTrait();
                    break;
            }
        }
    }

    private function parseNamespace() : void
    {

    }

    private function parseClass() : void
    {

    }

    private function parseFunction() : void
    {

    }

    private function parseUse() : void
    {

    }

    private function parseTrait() : void
    {

    }

    private function parseInterface() : void
    {

    }
}
