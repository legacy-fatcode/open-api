<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http;

use FatCode\OpenApi\Exception\Http\ServerException;
use FatCode\OpenApi\Http\Server\DispatchMode;
use FatCode\OpenApi\Http\Server\Listener;
use FatCode\OpenApi\Http\Server\OnRequestListener;
use FatCode\OpenApi\Http\Server\OnStartListener;
use FatCode\OpenApi\Http\Server\OnStopListener;
use SplQueue;

class Server
{
    public const DEFAULT_ADDRESS = '0.0.0.0';
    public const DEFAULT_PORT = 80;
    /**
     * @var SplQueue[]
     */
    private $listeners;
    /**
     * @var mixed[]
     */
    private $settings = [];

    public function __construct()
    {
        $this->listeners = [
            OnRequestListener::class => new SplQueue(),
            OnStartListener::class => new SplQueue(),
            OnStopListener::class => new SplQueue(),
        ];
    }

    public function use(Listener $listener) : void
    {
        if ($listener instanceof OnRequestListener) {
            $this->listeners[OnRequestListener::class]->enqueue($listener);
        }
        if ($listener instanceof OnStartListener) {
            $this->listeners[OnStartListener::class]->enqueue($listener);
        }
        if ($listener instanceof OnStopListener) {
            $this->listeners[OnStopListener::class]->enqueue($listener);
        }
    }

    /**
     * Sets the number of worker processes.
     *
     * @param int $count
     */
    public function setWorkers(int $count = 1): void
    {
        $this->settings['worker_num'] = $count;
    }

    /**
     * Sets the number of requests processed by the worker process before process manager recycles it.
     * Once process is recycled (memory used by process is freed and process is killed) process manger
     * will spawn new worker.
     *
     * @param int $max
     */
    public function setMaxRequests(int $max = 0): void
    {
        $this->settings['max_request'] = $max;
    }

    /**
     * Sets the max tcp connection number of the server.
     *
     * @param int $max
     */
    public function setMaxConnections(int $max = 10000): void
    {
        $this->settings['max_conn'] = $max;
    }

    /**
     * Sets temporary dir for uploaded files
     *
     * @param string $dir
     */
    public function setUploadDir(string $dir): void
    {
        $this->settings['upload_tmp_dir'] = $dir;
    }

    /**
     * Set the output buffer size in the memory. The default value is 2M.
     * The data to send can't be larger than the $size every request.
     *
     * @param int $size bytes
     */
    public function setBufferOutputSize(int $size = 2 * 1024 * 1024): void
    {
        $this->settings['buffer_output_size'] = $size;
    }

    /**
     * Sets response compression level 0 - no compression 9 - high compression
     *
     * @param int $level
     */
    public function setResponseCompression(int $level = 0): void
    {
        $this->settings['compression_level'] = $level;
    }

    /**
     * Sets dispatch mode for child processes.
     *
     * @param DispatchMode $mode
     */
    public function setDispatchMode(DispatchMode $mode) : void
    {
        $this->settings['dispatch_mode'] = $mode->getValue();
    }

    /**
     * Allows server to be run as a background process.
     *
     * @param string $pidFile
     */
    public function enableDaemon(string $pidFile): void
    {
        if (!is_writable($pidFile) && !is_writable(dirname($pidFile))) {
            throw ServerException::forInvalidPidFile($pidFile);
        }

        $this->settings += [
            'daemonize' => true,
            'pid_file' => $pidFile,
        ];
    }

    public function start(string $address = self::DEFAULT_ADDRESS, int $port = self::DEFAULT_PORT) : void
    {

    }
}
