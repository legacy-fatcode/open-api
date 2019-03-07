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
     * @var string[]
     */
    private static $fileImports = [];

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

        $imports = self::getFileImports($class->getStartLine(), $class->getFileName(), $class->getNamespaceName());

        foreach ($imports as $alias => $namespace) {
            $instance->addImport($namespace, $alias);
        }

        return $instance;
    }

    public static function fromReflectionMethod(ReflectionMethod $method) : self
    {
        $instance = new self(
            Target::TARGET_METHOD,
            $method->getNamespaceName(),
            "{$method->getDeclaringClass()->getName()}::{$method->getName()}()"
        );

        $imports = self::getFileImports($method->getDeclaringClass()->getStartLine(), $method->getFileName(), $method->getNamespaceName());

        foreach ($imports as $alias => $namespace) {
            $instance->addImport($namespace, $alias);
        }

        return $instance;
    }

    public static function fromReflectionProperty(ReflectionProperty $property) : self
    {
        $instance = new self(
            Target::TARGET_PROPERTY,
            $property->getDeclaringClass()->getNamespaceName(),
            "{$property->getDeclaringClass()->getName()}::\${$property->getName()}"
        );

        $imports = self::getFileImports($property->getDeclaringClass()->getStartLine(), $property->getDeclaringClass()->getFileName(), $property->getDeclaringClass()->getNamespaceName());

        foreach ($imports as $alias => $namespace) {
            $instance->addImport($namespace, $alias);
        }

        return $instance;
    }

    public static function fromReflectionFunction(ReflectionFunction $function) : self
    {
        $instance = new self(
            Target::TARGET_FUNCTION,
            $function->getNamespaceName(),
            "{$function->getName()}()"
        );

        $imports = self::getFileImports($function->getStartLine(), $function->getFileName(), $function->getNamespaceName());

        foreach ($imports as $alias => $namespace) {
            $instance->addImport($namespace, $alias);
        }

        return $instance;
    }

    private static function getFileImports($startLine, $filename, $ns) : array
    {
        if (isset(self::$fileImports[$filename])) {
            return self::$fileImports[$filename];
        }

        if (empty($filename) || !is_file($filename) || !is_readable($filename)) {
            return self::$fileImports[$filename] = [];
        }

        $a = self::tokenizeSource($startLine, $filename, $ns);

        return self::$fileImports[$filename] = $a;
    }

    private static function tokenizeSource($startLine, $filename, $namespace)
    {
        $tokens = token_get_all(file_get_contents($filename));
        $builtNamespace = '';
        $buildingNamespace = false;
        $matchedNamespace = false;
        $useStatements = [];
        $record = false;
        $currentUse = [
            'class' => '',
            'as' => ''
        ];
        foreach ($tokens as $token) {
            if ($token[0] === T_NAMESPACE) {
                $buildingNamespace = true;
                if ($matchedNamespace) {
                    break;
                }
            }
            if ($buildingNamespace) {
                if ($token === ';') {
                    $buildingNamespace = false;
                    continue;
                }
                switch ($token[0]) {
                    case T_STRING:
                    case T_NS_SEPARATOR:
                        $builtNamespace .= $token[1];
                        break;
                }
                continue;
            }
            if ($token === ';' || !is_array($token)) {
                if ($record) {
                    $useStatements[] = $currentUse;
                    $record = false;
                    $currentUse = [
                        'class' => '',
                        'as' => ''
                    ];
                }
                continue;
            }
            if ($token[0] === T_CLASS) {
                break;
            }
            if (strcasecmp($builtNamespace, $namespace) === 0) {
                $matchedNamespace = true;
            }
            if ($matchedNamespace) {
                if ($token[0] === T_USE) {
                    $record = 'class';
                }
                if ($token[0] === T_AS) {
                    $record = 'as';
                }
                if ($record) {
                    switch ($token[0]) {
                        case T_STRING:
                        case T_NS_SEPARATOR:
                            if ($record) {
                                $currentUse[$record] .= $token[1];
                            }
                            break;
                    }
                }
            }
            if ($token[2] >= $startLine) {
                break;
            }
        }
        $result = [];
        // Make sure the as key has the name of the class even
        // if there is no alias in the use statement.
        foreach ($useStatements as &$useStatement) {
            if (empty($useStatement['as'])) {

                $useStatement['as'] = $useStatement['class'];
                $result[$useStatement['class']] = $useStatement['class'];
            } else {
                $result[$useStatement['as']] = $useStatement['class'];
            }
        }

        return $result;
    }
}
