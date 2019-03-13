<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\Parser\MetaData;

class Attribute
{
    private $name;
    private $required;
    private $type;
    private $enum;
    private $validate = true;

    public function __construct(string $name, $type = 'mixed', bool $required = true)
    {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
    }

    public function disableValidation() : bool
    {
        $this->validate = false;
    }

    public function isRequired() : bool
    {
        return $this->required;
    }

    public function isEnum() : bool
    {
        return $this->enum !== null;
    }

    public function enumerate(array $values) : void
    {
        $this->enum = $values;
    }

    public function validate($value) : bool
    {
        if (!$this->validate) {
            return true;
        }

        if ($this->required && $value === null) {
            return false;
        }

        if ($value === null) {
            return true;
        }

        if ($this->isEnum()) {
            if (!is_array($value)) {
                $value = [$value];
            }
            foreach ($value as $item) {
                if (!in_array($item, $this->enum)) {
                    return false;
                }
            }
        }

        if (!$this->validateType($value, $this->type)) {
            return false;
        }

        return true;
    }

    private function validateType($type, $value) : bool
    {
        switch (true) {
            case $type === 'mixed' || $type === ['mixed']:
                return true;
            case $type === 'string':
                return is_string($value);
            case $type === 'boolean':
            case $type === 'bool':
                return is_bool($value);
            case $type === 'int':
            case $type === 'integer':
                return is_int($value);
            case $type === 'double':
            case $type === 'float':
                return is_float($value);
            case $type === 'object':
                return is_object($value);
            case is_array($type):
                if (!is_array($value)) {
                    return false;
                }
                foreach ($value as $item) {
                    if (!$this->validateType(end($type), $item)) {
                        return false;
                    }
                }
                return true;
            case class_exists($type):
                return $value instanceof $type;

            // Ignore unknown type annotation
            default:
                return true;
        }
    }
}
