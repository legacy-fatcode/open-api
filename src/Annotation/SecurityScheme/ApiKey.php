<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\SecurityScheme;

use Igni\OpenApi\Annotation\Annotation;

/**
 * @Annotation
 */
class ApiKey extends SecurityScheme
{
    /**
     * The name of the header, query or cookie parameter to be used.
     * @var string
     */
    public $name;

    /**
     * The location of the API key. Valid values are: "query", "header" or "cookie".
     * @var string
     */
    public $in;

    public function __construct()
    {
        $this->type = 'apiKey';
    }

    protected function getRequiredParameters() : array
    {
        return ['in', 'name'];
    }
}