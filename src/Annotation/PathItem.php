<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target(Target::TARGET_CLASS | Target::TARGET_METHOD)
 */
class PathItem extends Annotation
{
    /**
     * An optional, string summary, intended to apply to all operations in this path.
     * @var string
     */
    public $summary;

    /**
     * An optional, string description, intended to apply to all operations in this path. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * A definition of a GET operation on this path.
     * @var \FatCode\OpenApi\Annotation\PathItem\Operation
     */
    public $get;

    /**
     * A definition of a POST operation on this path.
     * @var \FatCode\OpenApi\Annotation\PathItem\Operation
     */
    public $post;

    /**
     * A definition of a PUT operation on this path.
     * @var \FatCode\OpenApi\Annotation\PathItem\Operation
     */
    public $put;

    /**
     * A definition of a DELETE operation on this path.
     * @var \FatCode\OpenApi\Annotation\PathItem\Operation
     */
    public $delete;

    /**
     * A definition of an OPTIONS operation on this path.
     * @var \FatCode\OpenApi\Annotation\PathItem\Operation
     */
    public $options;

    /**
     * A definition of a HEAD operation on this path.
     * @var \FatCode\OpenApi\Annotation\PathItem\Operation
     */
    public $head;

    /**
     * A definition of a PATCH operation on this path.
     * @var \FatCode\OpenApi\Annotation\PathItem\Operation
     */
    public $patch;

    /**
     * A definition of a TRACE operation on this path.
     * @var \FatCode\OpenApi\Annotation\PathItem\Operation
     */
    public $trace;

    /**
     * @var \FatCode\OpenApi\Annotation\Parameter[]
     */
    public $parameters = [];

    /**
     * An alternative server array to service all operations in this path.
     * @var \FatCode\OpenApi\Annotation\Reference[]
     */
    public $servers = [];
}
