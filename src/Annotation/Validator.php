<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Igni\Validation\Exception\InvalidArgumentException;
use Igni\Validation\Assertion;
use Igni\Validation\ValidationError;
use ArrayAccess;

class Validator
{
    /** @var Assertion[] */
    private $rules;

    /**
     * @param array[string, string] $rules
     */
    public function __construct(array $rules)
    {
        $this->validateRules($rules);

        $this->rules = $rules;
    }

    /**
     * @param Annotation $input
     * @return bool
     */
    public function assert(Annotation $input): bool
    {
        $this->errors = [];
        if (!is_array($input) && !$input instanceof ArrayAccess) {
            return false;
        }
        foreach ($this->rules as $name => $rule) {
            if (!$rule->validate($input[$name] ?? null)) {
                $this->errors[] = $rule->getErrors()[0];
            }
        }

        if ($this->errors) {
            return false;
        }

        return true;
    }

    /**
     * @return ValidationError[]
     */
    public function getErrors(): array
    {
        // Remove the last assertion failure, as we dont need it
        $failures = parent::getErrors();
        array_pop($failures);

        return $failures;
    }

    private function validateRules(array $rules): void
    {
        foreach($rules as $name => $rule) {
            if (!$rule instanceof Assertion) {
                throw InvalidArgumentException::forInvalidAssertion($rule);
            }
            $rule->setName($name);
        }
    }
}

