<?php declare(strict_types=1);

namespace FatCode\OpenApi\Analyzer;

use FatCode\OpenApi\File\PhpFile;

class PhpFileInfo
{
    private $file;

    private $namespaces;

    private $classes;

    private $functions;

    public function __construct(PhpFile $file, array $namespaces, array $classes, array $functions)
    {
        $this->file = $file;
        $this->namespaces = $namespaces;
        $this->classes = $classes;
        $this->functions = $functions;
    }

    public function getFile(): PhpFile
    {
        return $this->file;
    }

    public function getNamespaces() : array
    {
        return $this->namespaces;
    }

    public function getClasses() : array
    {
        return $this->classes;
    }

    public function getFunctions() : array
    {
        return $this->functions;
    }
}
