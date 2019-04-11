<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer\Parser;

use FatCode\OpenApi\File\PhpFile;

class ClassParser implements PhpFileParser
{
    public function parse(PhpFile $file) : array
    {
        $classes = [];

        for ($cursor = 0; $cursor < $file->countTokens(); $cursor++) {
            $token = $file->getTokenAt($cursor);

            if (!is_array($token) || $token[0] !== T_CLASS) {
                continue;
            }

            $classes[] = $this->parseClassAt($cursor, $file);
        }

        return $classes;
    }

    private function parseClassAt(int $cursor, PhpFile $file): string
    {
        $cursor = $this->seekCursorForToken(T_STRING, $cursor, $file);
        return $file->getTokenAt($cursor)[1];
    }

    private function seekCursorForToken(int $seekedToken, int $cursor, PhpFile $file) : int
    {
        while ($cursor++ < $file->countTokens()) {
            $token = $file->getTokenAt($cursor);

            if (!is_array($token)) {
                continue;
            }

            if ($token[0] === $seekedToken) {
                break;
            }
        }

        return $cursor;
    }
}
