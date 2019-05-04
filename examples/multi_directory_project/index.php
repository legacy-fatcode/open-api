<?php declare(strict_types=1);

namespace MultiDirectory;

use FatCode\OpenApi\Annotation as Api;

/**
 * Main application class.
 *
 * @Api\Application(
 *     title = "Your application title",
 *     version = "1.0.0",
 *     servers = [
 *         @Api\Server(
 *             id = "development",
 *             port = 8080,
 *             host = "localhost"
 *         )
 *     ]
 * )
 */
class Application
{

}
