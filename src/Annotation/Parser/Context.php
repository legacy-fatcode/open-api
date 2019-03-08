<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation\Parser;

use Igni\OpenApi\Annotation\Parser\Annotation\Target;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;

class Context
{
    /**
     * @var string
     */
    private $symbol;

    /**
     * @var string[]
     */
    private $imports = [];

    private $target;

    private $namespace;

    public function __construct(
        string $target = Target::TARGET_ALL,
        string $namespace = '',
        string $symbol = ''
    ) {
        $this->target = $target;
        $this->symbol = $symbol;
        $this->namespace = $namespace;
    }

    public function getNamespace() : string
    {
        return $this->namespace;
    }

    public function getSymbol() : string
    {
        return $this->symbol;
    }

    public function addImport(string $name, string $alias = '') : void
    {
        $this->imports[$alias] = $name;
    }

    public function getTarget() : string
    {
        return $this->target;
    }

    public function getImports() : array
    {
        return $this->imports;
    }

    public function __toString() : string
    {
        return $this->symbol;
    }

    public static function fromReflectionClass(ReflectionClass $class) : self
    {
        $instance = new self(
            Target::TARGET_CLASS,
            $class->getNamespaceName(),
            $class->getName()
        );
        $imports = new ReflectorImports($class);
        $instance->imports = $imports->getImports();

        return $instance;
    }

    public static function fromReflectionMethod(ReflectionMethod $method) : self
    {
        $instance = new self(
            Target::TARGET_METHOD,
            $method->getNamespaceName(),
            "{$method->getDeclaringClass()->getName()}::{$method->getName()}()"
        );
        $imports = new ReflectorImports($method);
        $instance->imports = $imports->getImports();

        return $instance;
    }

    public static function fromReflectionProperty(ReflectionProperty $property) : self
    {
        $instance = new self(
            Target::TARGET_PROPERTY,
            $property->getDeclaringClass()->getNamespaceName(),
            "{$property->getDeclaringClass()->getName()}::\${$property->getName()}"
        );
        $imports = new ReflectorImports($property);
        $instance->imports = $imports->getImports();

        return $instance;
    }

    public static function fromReflectionFunction(ReflectionFunction $function) : self
    {
        $instance = new self(
            Target::TARGET_FUNCTION,
            $function->getNamespaceName(),
            "{$function->getName()}()"
        );
        $imports = new ReflectorImports($function);
        $instance->imports = $imports->getImports();

        return $instance;
    }
}
