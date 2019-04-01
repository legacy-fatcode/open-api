<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use FatCode\OpenApi\Exception\ProjectAnalyzerException;
use Throwable;

use function count;

class FileAnalyzer
{
    private $fileName;
    private $cursor;
    private $tokens;
    private $eof;
    private $currentNamespace;
    private $declaredClasses = [];
    private $declaredFunctions = [];

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
        $this->tokens = token_get_all(file_get_contents($this->fileName));
        $this->eof = count($this->tokens);
    }

    public function getFileName() : string
    {
        return $this->fileName;
    }

    public function analyze() : void
    {
        for ($this->cursor = 0; $this->cursor < $this->eof; $this->cursor++) {
            $token = $this->getCurrentToken();
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
            }
        }

        $a = 1;
    }

    private function getCurrentToken()
    {
        return $this->tokens[$this->cursor];
    }

    private function parseNamespace() : void
    {
        $this->currentNamespace = '';
        while ($this->cursor++ < $this->eof) {
            $token = $this->getCurrentToken();
            if (is_array($token) && ($token[0] === T_STRING || $token[0] === T_NS_SEPARATOR)) {
                $this->currentNamespace .= $token[1];
            }
            // Namespace can end either with { or ;
            if ($token === ';' || $token === '{') {
                return;
            }
        }
    }

    private function parseClass() : void
    {
        $this->seekToken(T_STRING);
        $className = $this->getCurrentToken()[1];
        // Skip extend, implements and other keywords
        $this->seekStartOfBlock();
        $this->seekEndOfBlock();

        $this->declaredClasses[] = $className;
    }

    private function parseFunction() : void
    {

    }

    private function seekToken(int $type) : void
    {
        while ($this->cursor++ < $this->eof) {
            $token = $this->getCurrentToken();
            if (!is_array($token)) {
                continue;
            }
            if ($token[0] === $type) {
                break;
            }
        }
    }

    private function seekValue(string $value) : void
    {
        while ($this->cursor++ < $this->eof) {
            $token = $this->getCurrentToken();
            if (!is_array($token)) {
                if ($token === $value) {
                    break;
                }
                continue;
            }
            if ($token[1] === $value) {
                break;
            }
        }
    }

    private function seekStartOfBlock() : void
    {
        $this->seekValue('{');
    }

    private function seekEndOfBlock() : void
    {
        $depth = 1;
        while ($this->cursor++ < $this->eof & $depth > 0) {
            $token = $this->getCurrentToken();
            if (is_array($token)) {
                continue;
            }
            if ($token === '}') {
                $depth--;
            }
            if ($depth === '{') {
                $depth++;
            }
        }
    }
}
