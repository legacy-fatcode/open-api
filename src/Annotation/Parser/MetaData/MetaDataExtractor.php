<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser\MetaData;

use Igni\OpenApi\Annotation\Parser\Annotation\Annotation;
use Igni\OpenApi\Annotation\Parser\Annotation\Enum;
use Igni\OpenApi\Annotation\Parser\Annotation\NoValidate;
use Igni\OpenApi\Annotation\Parser\Annotation\Required;
use Igni\OpenApi\Annotation\Parser\Annotation\Target;
use Igni\OpenApi\Annotation\Parser\Context;
use Igni\OpenApi\Annotation\Parser\Parser;
use Igni\OpenApi\Exception\ParserException;
use ReflectionClass;
use ReflectionProperty;

class MetaDataExtractor
{
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function extract(ReflectionClass $class, Context $context) : array
    {
        $annotationContext = Context::fromReflectionClass($class);
        $annotations = $this->parser->parse($class->getDocComment(), $annotationContext);

        $metaData = [
            'target' => [Target::TARGET_ALL],
            'validate' => true,
            'has_constructor' => $class->getConstructor() !== null,
            'is_annotation' => false,
        ];

        foreach ($annotations as $annotation) {
            switch (get_class($annotation)) {
                case Annotation ::class:
                    $metaData['is_annotation'] = true;
                    break;
                case Target::class:
                    foreach ($annotation->value as $target) {
                        if (in_array($target, Target::TARGETS)) {
                            throw ParserException::forPropertyValidationFailure(
                                $annotationContext,
                                ['enum' => Target::TARGETS],
                                $annotation->value
                            );
                        }
                    }
                    $metaData['target'] = $annotation->value;
                    break;
                case NoValidate::class:
                    $metaData['validate'] = (bool) $annotation->value;
                    break;
            }
        }
        return $metaData;
        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyContext = Context::fromReflectionProperty($property);
            $docComment = $property->getDocComment();
            $name = $property->getName();
            $type = $this->parseDeclaredType($docComment, $context);
            $required = false;
            $validate = true;
            $enum = null;

            $annotations = $this->parser->parse($docComment, $propertyContext);
            foreach ($annotations as $annotation) {
                switch (get_class($annotation)) {
                    case Enum::class:
                        $enum = $annotation->value;
                        break;
                    case Required::class:
                        $required = (bool) $annotation->value;
                        break;
                    case NoValidate::class:
                        $validate = (bool) $annotation->value;
                        break;
                }
            }

            $attribute = new Attribute($name, $type, $required);
            if (!$validate) {
                $attribute->disableValidation();
            }
            if ($enum) {
                $attribute->enumerate($enum);
            }
            $metaData['properties'][$name] = $attribute;
        }

        return $metaData;
    }

    private function parseDeclaredType(string $docComment, Context $context)
    {
        preg_match('/\@var\s+?([^\[\n\*]+)(\[\s*?\])?/', $docComment, $matches);
        $type = trim($matches[1]);
        $isArray = isset($matches[2]);
        switch (true) {
            // @var annotation contains multiple viable types for the property so it is mixed, we dont care about it
            case strstr($type, '|') !== false:
                $type = 'mixed';
                break;
            // Primitive types
            case in_array($type, ['float', 'double', 'bool', 'boolean', 'string', 'object', 'mixed']):
                if ($isArray) {
                    $type = [$type];
                }
                break;
            // If class like that exists
            case class_exists($type):
                if ($isArray) {
                    $type = [$type];
                }
                break;
            case ($class = $this->resolveFullyQualifiedClassName($type, $context)) !== null:
                $type = $class;
                if ($isArray) {
                    $type = [$class];
                }
                break;
            // Fallback to mixed
            default:
                $type = 'mixed';
                break;
        }

        return $type;
    }

    private function resolveFullyQualifiedClassName(string $identifier, Context $context) : ?string
    {
        if (class_exists($identifier)) {
            return $identifier;
        }

        $identifier = explode('\\', $identifier);
        $imports = $context->getImports();
        if (isset($imports[$identifier[0]])) {
            $identifier = array_merge(explode('\\', $imports[$identifier[0]]), array_slice($identifier, 1));
        }
        $identifier = implode('\\', $identifier);
        if (class_exists($identifier)) {
            return $identifier;
        }

        return null;
    }
}
