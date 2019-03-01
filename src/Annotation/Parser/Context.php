<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

class Context
{
    public const CONTEXT_FUNCTION = 'FUNCTION';
    public const CONTEXT_METHOD = 'METHOD';
    public const CONTEXT_CLASS = 'CLASS';
    public const CONTEXT_PROPERTY = 'PROPERTY';

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $symbolType;

    /**
     * @var string
     */
    private $symbolName;

    public function __construct(string $filename, string $symbolType = '', string $symbolName = '')
    {
        $this->filename = $filename;
        $this->symbolType = $symbolType;
        $this->symbolName = $symbolName;
    }

    public function getFilename() : string
    {
        return $this->filename;
    }

    public function getSymbolType() : string
    {
        return $this->symbolType;
    }

    public function getSymbolName() : string
    {
        return $this->symbolName;
    }
}