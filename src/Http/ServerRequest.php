<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http;

use FatCode\OpenApi\Exception\Http\HttpException;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

class ServerRequest extends Request implements ServerRequestInterface
{
    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var array
     */
    private $cookieParams = [];

    /**
     * @var null|array|object
     */
    private $parsedBody;

    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var array
     */
    private $serverParams;

    /**
     * @var array
     */
    private $uploadedFiles;

    public function __construct(
        $uri = null,
        HttpMethod $method = null,
        $body = 'php://input',
        array $headers = [],
        array $uploadedFiles = [],
        array $serverParams = []
    ) {
        parent::__construct($uri, $method, $body, $headers);
        $this->validateUploadedFiles($uploadedFiles);
        $this->serverParams  = $serverParams;
        $this->uploadedFiles = $uploadedFiles;
        parse_str($this->getUri()->getQuery(), $this->queryParams);
        $this->parseBody();
    }

    private function parseBody() : void
    {
        $contentType = $this->getHeader('Content-Type')[0] ?? '';

        $body = (string) $this->getBody();

        switch (strtolower($contentType)) {
            case 'application/json':
                $this->parsedBody = json_decode($body, true);
                return;
            case 'application/x-www-form-urlencoded':
                parse_str($body, $this->parsedBody);
                return;
            case 'application/xml':
                $this->parsedBody = simplexml_load_string($body);
                return;
            case 'text/csv':
                $this->parsedBody = str_getcsv($body);
                return;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getServerParams() : array
    {
        return $this->serverParams;
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadedFiles() : array
    {
        return $this->uploadedFiles;
    }

    /**
     * {@inheritdoc}
     */
    public function withUploadedFiles(array $uploadedFiles) : self
    {
        $this->validateUploadedFiles($uploadedFiles);
        $new = clone $this;
        $new->uploadedFiles = $uploadedFiles;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getCookieParams() : array
    {
        return $this->cookieParams;
    }

    /**
     * {@inheritdoc}
     */
    public function withCookieParams(array $cookies) : self
    {
        $new = clone $this;
        $new->cookieParams = $cookies;
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParams() : array
    {
        return $this->queryParams;
    }

    /**
     * {@inheritdoc}
     */
    public function withQueryParams(array $query) : self
    {
        $new = clone $this;
        $new->queryParams = $query;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    /**
     * {@inheritdoc}
     */
    public function withParsedBody($data) : self
    {
        $new = clone $this;
        $new->parsedBody = $data;
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($attribute, $default = null)
    {
        if (! isset($this->attributes[$attribute])) {
            return $default;
        }

        return $this->attributes[$attribute];
    }

    /**
     * {@inheritdoc}
     */
    public function withAttribute($attribute, $value) : self
    {
        $new = clone $this;
        $new->attributes[$attribute] = $value;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutAttribute($attribute) : self
    {
        if (!isset($this->attributes[$attribute])) {
            return clone $this;
        }

        $new = clone $this;
        unset($new->attributes[$attribute]);
        return $new;
    }

    /**
     * Sets request attributes
     *
     * This method returns a new instance.
     *
     * @param array $attributes
     * @return self
     */
    public function withAttributes(array $attributes) : self
    {
        $new = clone $this;
        $new->attributes = $attributes;
        return $new;
    }

    /**
     * Recursively validate the structure in an uploaded files array.
     *
     * @param array $uploadedFiles
     * @throws HttpException if any leaf is not an UploadedFileInterface instance.
     */
    private function validateUploadedFiles(array $uploadedFiles) : void
    {
        foreach ($uploadedFiles as $file) {
            if (is_array($file)) {
                $this->validateUploadedFiles($file);
                continue;
            }

            if (!$file instanceof UploadedFileInterface) {
                throw new InvalidArgumentException('Invalid leaf in uploaded files structure');
            }
        }
    }
}
