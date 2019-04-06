<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http\Server;

use FatCode\Enum;

final class DispatchMode extends Enum
{
    /**
     * Dispatch the connection to the workers in sequence.
     * Recommended for stateless asynchronous server.
     */
    public const POLLING = 1;

    /**
     * Dispatch the connection to the worker according to the id number of connection.
     * In this mode, the data from the same connection will be handled by the same worker process.
     * Recommended for stateful server.
     */
    public const FIXED = 2;

    /**
     * Dispatch the connection to the unoccupied worker process.
     * Recommended for stateless, synchronous and blocking server.
     */
    public const PREEMPTIVE = 3;

    /**
     * Dispatch the connection to the worker according to the ip of client.
     * The dispatch algorithm is ip2long(ClientIP) % worker_num
     */
    public const IP = 4;
}
