<?php declare(strict_types=1);

namespace Igni\OpenApi\Annotation;

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

    public function validate() : void
    {
        // By default all annotations are valid.
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
