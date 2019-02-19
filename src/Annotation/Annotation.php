<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

/**
 * Base annotation class
 */
abstract class Annotation
{
    abstract protected function getRequiredParameters() : array;

    public function toYaml() : string
    {

    }

    public function toJson() : string
    {

    }

    private function validateDefaultTypes(string $type, $value): bool
    {
        switch ($type) {
            case 'string':
                return is_string($value);
            case 'boolean':
                return is_bool($value);
            case 'integer':
                return is_int($value);
            case 'number':
                return is_numeric($value);
            case 'object':
                return is_object($value);
            case 'array':
                return $this->validateArrayType($value);
            case 'scheme':
                return in_array($value, ['http', 'https', 'ws', 'wss'], true);
            default:

        }
    }

    private function validateArrayType($value): bool
    {
        if (is_array($value) === false) {
            return false;
        }
        $count = 0;
        foreach ($value as $i => $item) {
            if ($count !== $i) {
                return false;
            }
            $count++;
        }
        return true;
    }
}
