<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

/**
 * Base annotation class
 */
abstract class Annotation
{
    protected const TYPE_STRING = 'string';
    protected const TYPE_BOOL = 'boolean';
    protected const TYPE_INTEGER = 'integer';
    protected const TYPE_NUMBER = 'number';
    protected const TYPE_OBJECT = 'object';
    protected const TYPE_CLASS = 'class';
    protected const TYPE_SCHEME = 'scheme';


    abstract protected function getRequiredParameters() : array;
    abstract protected function getParametersType() : array;

    public function toYaml() : string
    {

    }

    public function toJson() : string
    {

    }

    public function validate() : bool
    {
        $validateTypes = $this->getParametersType();
        foreach ($validateTypes as $name => $type) {
            
        }
    }
}
