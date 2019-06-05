<?php declare(strict_types=1);

namespace FatCode\OpenApi\Serialization;

use FatCode\OpenApi\SchemaObject\SchemaObject;
use FatCode\OpenApi\SchemaPrimitive\SchemaPrimitive;
use ReflectionClass;

final class SchemaObjectSerializer
{
    public function serializeIntoArray(SchemaObject $schemaObject) : array
    {
        $serializedObject = [];
        $constructor = (new ReflectionClass($schemaObject))->getConstructor();

        foreach ($constructor->getParameters() as $parameter) {
            $parameterName = $parameter->getName();
            $parameterValue = $this->callGetterForParameter($parameterName, $schemaObject);
//            $parameterType = $parameter->getClass()
//                ? $parameter->getClass()->getName()
//                : $parameter->getType();

            if ($parameterValue instanceof SchemaObject) {
                $parameterValue = $this->serializeIntoArray($parameterValue);
            } elseif ($parameterValue instanceof SchemaPrimitive) {
                $parameterValue = $parameterValue->getValue();
            } else {
                throw SchemaObjectSerializerException::invalidParameterValueType(get_class($parameterValue));
            }

            $serializedObject[$parameterName] = $parameterValue;
        }

        return $serializedObject;
    }

    private function callGetterForParameter(string $parameterName, SchemaObject $schemaObject)
    {
        $parameterName = ucfirst($parameterName);
        $getterName = "get$parameterName";

        return call_user_func([$schemaObject, $getterName]);
    }

    public function serializeIntoJson(Object $object) : string
    {
        return json_encode($this->serializeIntoArray($object));
    }
}