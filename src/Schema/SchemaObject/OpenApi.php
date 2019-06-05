<?php declare(strict_types=1);

namespace FatCode\OpenApi\Schema\SchemaObject;

final class OpenApi
{
    /** @var string */
    private $openapi;

    /** @var Info */
    private $info;

    /** @var ServerCollection | null */
    private $servers;

    /** @var Paths */
    private $paths;

    /** @var Components | null */
    private $components;

    /** @var SecurityRequirement | null */
    private $security;

    /** @var Tag[] | null */
    private $tags;

    /** @var ExternalDocumentation | null */
    private $externalDocs;

    // /** @var SpecitifactionExtension[] | null */
    // private $specificationExtensions;

    public function __construct(
        string $openapi,
        Info $info,
        ?ServerCollection $servers,
        Paths $paths,
        ?Components $components,
        ?SecurityRequirement $security,
        ?TagCollection $tags,
        ?ExternalDocumentation $externalDocs
    ) {
        $this->openapi = $openapi;
        $this->info = $info;
        $this->servers = $servers;
        $this->paths = $paths;
        $this->components = $components;
        $this->security = $security;
        $this->tags = $tags;
        $this->externalDocs = $externalDocs;
    }
}
