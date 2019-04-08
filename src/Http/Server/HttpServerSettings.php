<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http\Server;

use FatCode\OpenApi\Exception\Http\ServerException;

class HttpServerSettings
{
    private $address;
    private $port;
    private $workers;
    private $maxRequests;
    private $maxConnections;
    private $uploadDir;
    private $bufferOutputSize;
    private $compressionLevel;
    private $dispatchMode;
    private $pidFile;

    public function __construct(string $address = '0.0.0.0', int $port = 80)
    {
        $this->address = $address;
        $this->port = $port;
    }

    /**
     * Sets the port for server to listen.
     *
     * @param int $port
     */
    public function setPort(int $port) : void
    {
        $this->port = $port;
    }

    /**
     * Sets the number of worker processes.
     *
     * @param int $count
     */
    public function setWorkers(int $count = 1) : void
    {
        $this->workers = $count;
    }

    /**
     * Sets the number of requests processed by the worker process before process manager recycles it.
     * Once process is recycled (memory used by process is freed and process is killed) process manger
     * will spawn new worker.
     *
     * @param int $max
     */
    public function setMaxRequests(int $max = 0) : void
    {
        $this->maxRequests = $max;
    }

    /**
     * Sets the max tcp connection number of the server.
     *
     * @param int $max
     */
    public function setMaxConnections(int $max = 10000) : void
    {
        $this->maxConnections = $max;
    }

    /**
     * Sets temporary dir for uploaded files
     *
     * @param string $dir
     */
    public function setUploadDir(string $dir) : void
    {
        $this->uploadDir = $dir;
    }

    /**
     * Set the output buffer size in the memory. The default value is 2M.
     * The data to send can't be larger than the $size every request.
     *
     * @param int $size bytes
     */
    public function setBufferOutputSize(int $size = 2 * 1024 * 1024) : void
    {
        $this->bufferOutputSize = $size;
    }

    /**
     * Sets response compression level 0 - no compression 9 - high compression
     *
     * @param int $level
     */
    public function setResponseCompression(int $level = 0) : void
    {
        $this->compressionLevel = $level;
    }

    /**
     * Sets dispatch mode for child processes.
     *
     * @param DispatchMode $mode
     */
    public function setDispatchMode(DispatchMode $mode) : void
    {
        $this->dispatchMode = $mode;
    }

    /**
     * Allows server to be run as a background process.
     *
     * @param string $pidFile
     */
    public function setPidFile(string $pidFile): void
    {
        if (!is_writable($pidFile) && !is_writable(dirname($pidFile))) {
            throw ServerException::forInvalidPidFile($pidFile);
        }

        $this->pidFile = $pidFile;
    }

    /**
     * @return string
     * @see __construct()
     */
    public function getAddress() : string
    {
        return $this->address;
    }

    /**
     * @return int
     * @see setPort
     */
    public function getPort() : int
    {
        return $this->port;
    }

    /**
     * @return int
     * @see setWorkers
     */
    public function getWorkers() : int
    {
        return $this->workers;
    }

    public function getMaxRequests() : int
    {
        return $this->maxRequests;
    }

    public function getMaxConnections() : int
    {
        return $this->maxConnections;
    }

    public function getUploadDir() : string
    {
        return $this->uploadDir;
    }

    public function getBufferOutputSize() : int
    {
        return $this->bufferOutputSize;
    }

    public function getCompressionLevel() : int
    {
        return $this->compressionLevel;
    }

    public function getDispatchMode() : DispatchMode
    {
        return $this->dispatchMode;
    }

    public function getPidFile() : string
    {
        return $this->pidFile;
    }

    public function toArray() : array
    {
        $output = [
            'address' => $this->address,
            'port' => $this->port,
        ];

        if (isset($this->workers)) {
            $output['workers'] = $this->workers;
        }

        if (isset($this->maxRequests)) {
            $output['max_requests'] = $this->maxRequests;
        }

        if (isset($this->maxConnections)) {
            $output['max_connections'] = $this->maxConnections;
        }

        if (isset($this->uploadDir)) {
            $output['output_dir'] = $this->uploadDir;
        }

        if (isset($this->bufferOutputSize)) {
            $output['buffer_output_size'] = $this->bufferOutputSize;
        }

        if (isset($this->compressionLevel)) {
            $output['response_compression_level'] = $this->compressionLevel;
        }

        if (isset($this->dispatchMode)) {
            $output['dispatch_mode'] = $this->dispatchMode->getValue();
        }

        if (isset($this->pidFile)) {
            $output['pid_file'] = $this->pidFile;
        }

        return $output;
    }
}
