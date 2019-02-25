<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Igni\OpenApi\Exception\AnnotationException;

/**
 * Base annotation class
 */
abstract class Annotation
{
    protected const TYPE_MIXED = 'mixed';
    protected const TYPE_STRING = 'string';
    protected const TYPE_BOOL = 'boolean';
    protected const TYPE_INTEGER = 'integer';
    protected const TYPE_NUMBER = 'number';
    protected const TYPE_OBJECT = 'object';
    protected const TYPE_CLASS = 'class';

    abstract protected function getAttributesSchema() : array;

    public function toYaml() : string
    {

    }

    public function toJson() : string
    {

    }

    public function validate() : void
    {
        foreach ($this->getAttributesSchema() as $name => $schema) {
            if (!isset($schema['type'])) {
                throw AnnotationException::forMissingPropertyType($this, $name);
            }

            $set = isset($this->{$name}) && $this->{$name} !== null;
            if (isset($schema['required']) && $schema['required'] && !$set) {
                throw AnnotationException::forMissingProperty($this, $name);
            }

            if ($set && !$this->validateType($schema['type'], $this->{$name})) {
                throw AnnotationException::forInvalidPropertyValue($this, $name);
            }
        }
    }

    protected function interpolateString(string $string) : string
    {
        return preg_replace_callback(
            '/\\{([^\\}]+)\\}/',
            function(array $match) {

            },
            $string
        );
    }

    private function validateType($type, $value) : bool
    {
        switch (true) {
            case $type === self::TYPE_STRING:
                return is_string($value);
            case $type === self::TYPE_BOOL:
                return is_bool($value);
            case $type === self::TYPE_INTEGER:
                return is_int($value);
            case $type === self::TYPE_NUMBER:
                return is_numeric($value);
            case $type === self::TYPE_OBJECT:
                return is_object($value);
            case $type === self::TYPE_CLASS:
                return class_exists($value);
            // Validate hash
            case is_array($type) && count($type) === 2:
                foreach ($value as $key => $item) {
                    if (!$this->validateType($type[0], $key) || !$this->validateType($type[1], $item)) {
                        return false;
                    }
                }
                return true;
            // Validate list
            case is_array($type) && count($type) === 1:
                foreach ($value as $item) {
                    if (!$this->validateType($type[0], $item)) {
                        return false;
                    }
                }
                return true;
            case is_string($type) && class_exists($type):
                return $value instanceof $type;
            default:
                throw AnnotationException::forInvalidPropertyType($this, $type);
        }
    }
}
