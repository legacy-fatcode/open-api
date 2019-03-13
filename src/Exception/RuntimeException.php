<?php declare(strict_types=1);

namespace FatCode\OpenApi\Exception;

/**
 * Exception thrown if an error which can only be found on runtime occurs.
 */
interface RuntimeException extends OpenApiException
{
}
