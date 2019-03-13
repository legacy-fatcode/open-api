<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_ANNOTATION)
 */
final class Example extends Annotation
{
    /**
     * @var string
     */
    public $id;

    /**
     * Short description for the example.
     * @var string
     */
    public $summary;

    /**
     * Long description for the example. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * Embedded literal example. The value field and externalValue field are mutually exclusive.
     * @var mixed
     */
    public $value;

    /**
     * A URL that points to the literal example. This provides the capability to reference examples that cannot easily be included in JSON or YAML documents. The value field and externalValue field are mutually exclusive.
     * @var string
     */
    public $externalValue;
}
