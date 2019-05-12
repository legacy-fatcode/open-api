<?php declare(strict_types=1);

namespace FatCode\OpenApi\Annotation\Operation;

use FatCode\OpenApi\Annotation\Parameter;
use FatCode\OpenApi\Annotation\Request;
use FatCode\OpenApi\Annotation\Response;
use FatCode\OpenApi\Annotation\SecurityScheme\SecurityRequirement;

/**
 * @Annotation
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#operationObject
 */
abstract class Operation
{
    /**
     * Route that points the the resource.
     *
     * @var string
     * @Required
     */
    public $route;

    /**
     * The list of possible responses as they are returned from executing this operation.
     * @var Response[]
     * @Required
     */
    public $responses;

    /**
     * A list of tags for API documentation control.
     * Tags can be used for logical grouping of operations by resources or any other qualifier.
     *
     * @var string[]
     */
    public $tags;

    /**
     * A short summary of what the operation does.
     * @var string
     */
    public $summary;

    /**
     * A verbose explanation of the operation behavior. CommonMark syntax MAY be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * Additional external documentation for this operation.
     * @var string
     */
    public $externalDocs;

    /**
     * A list of parameters that are applicable for this operation.
     * @var Parameter[]
     */
    public $parameters;

    /**
     * The request body applicable for this operation.
     * @var Request
     */
    public $requestBody;

    /**
     * Declares this operation to be deprecated. Consumers SHOULD refrain from usage of the declared operation.
     * @var bool
     */
    public $deprecated = false;

    /**
     * @var SecurityRequirement
     */
    public $security;
}
