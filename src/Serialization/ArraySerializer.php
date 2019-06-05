<?php declare(strict_types=1);

namespace FatCode\OpenApi\Serialization;

use FatCode\OpenApi\Schema\SchemaObject\Schema;
use FatCode\OpenApi\Schema\SchemaObject\SchemaObject;
use FatCode\OpenApi\Schema\SchemaPrimitive\SchemaPrimitive;
use ReflectionClass;

final class ArraySerializer
{
    public function __invoke(Schema $schema) : array
    {
        $serializedSchema = [];
        $constructor = (new ReflectionClass($schema))->getConstructor();

        foreach ($constructor->getParameters() as $parameter) {
            $parameterName = $parameter->getName();
            $parameterValue = $this->callGetterForParameter($parameterName, $schema);
//            $parameterType = $parameter->getClass()
//                ? $parameter->getClass()->getName()
//                : $parameter->getType();

            if ($parameterValue instanceof SchemaObject) {
                $parameterValue = $this($parameterValue);
            } elseif ($parameterValue instanceof SchemaPrimitive) {
                $parameterValue = $parameterValue->getValue();
            } else {
                throw SerializerException::forUnexpectedParameterValueInstance(get_class($parameterValue));
            }

            $serializedSchema[$parameterName] = $parameterValue;
        }

        return $serializedSchema;
    }

    private function callGetterForParameter(string $parameterName, Schema $schema)
    {
        $parameterName = ucfirst($parameterName);
        $getterName = "get$parameterName";

        return call_user_func([$schema, $getterName]);
    }
}