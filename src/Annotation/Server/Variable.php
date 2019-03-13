<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\Server;

use FatCode\Annotation\Required;

/**
 * Represents server or environmental variable.
 * @Annotation
 */
class Variable
{
    /**
     * Variable name
     *
     * @Required
     * @var string
     */
    public $name;

    /**
     * An enumeration of string values to be used if the substitution options are from a limited set.
     *
     * @var string[]
     */
    public $enum;

    /**
     * The default value to use for substitution, and to send, if an alternate value is not supplied.
     *
     * @var string
     */
    public $default;

    /**
     * An optional description for the server variable.
     * CommonMark syntax MAY be used for rich text representation.
     *
     * @var string
     */
    public $description;
}
