<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

use Igni\OpenApi\Exception\AnnotationException;

/**
 * Base annotation class
 */
abstract class Annotation
{
    public function toYaml() : string
    {

    }

    public function toJson() : string
    {

    }

    protected function interpolateString(string $string) : string
    {
        return preg_replace_callback(
            '/\\{([^\\}]+)\\}/',
            function(array $match) {

            },
            $string
        );
    }
}
