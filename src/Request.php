<?php
declare(strict_types=1);

namespace Sapien;

use Sapien\Request\Accept;
use Sapien\Request\Authorization;
use Sapien\Request\Content;
use Sapien\Request\Forwarded;
use Sapien\Request\Method;
use Sapien\Request\Upload;
use Sapien\Request\Url;
use Sapien\Request\XForwarded;

/**
 * @property-read Accept $accept
 * @property-read Authorization $authorization
 * @property-read Forwarded[] $forwarded
 * @property-read XForwarded $xForwarded
 */
class Request extends ValueObject
{
    private readonly Accept $accept;

    private readonly Authorization\Scheme $authorization;

    public readonly Content $content;

    public readonly array $cookies;

    public readonly array $files;

    private readonly array $forwarded;

    public readonly array $headers;

    public readonly array $input;

    public readonly Method $method;

    public readonly array $query;

    public readonly array $server;

    public readonly array $uploads;

    public readonly Url $url;

    private readonly XForwarded $xForwarded;

    public function __construct(
        array $globals = null,
        string $method = null,
        array $url = null,
        Content|string|null $content = null,
    ) {
        $this->server = $this->newGlobal($globals['_SERVER'] ?? $_SERVER);
        $this->headers = $this->newHeaders();
        $this->method = $this->newMethod($method);
        $this->url = $this->newUrl($url);
        $this->content = $this->newContent($content);

        $this->cookies = $this->newGlobal($globals['_COOKIE'] ?? $_COOKIE);
        $this->files = $this->newGlobal($globals['_FILES'] ?? $_FILES);
        $this->input = $this->newGlobal(
            $globals['_POST']
            ?? $this->content->getParsedBody()
            ?? $_POST
        );
        $this->uploads = $this->newUploads();
        $this->query = $this->newGlobal($globals['_GET'] ?? $_GET);
    }

    public function __get(string $key) : mixed
    {
        if (! property_exists($this, $key)) {
            return parent::__get($key);
        }

        if (! isset($this->$key)) {
            $method = "new{$key}";
            $this->$key = $this->$method();
        }

        return $this->$key;
    }

    protected function newAccept() : Accept
    {
        return Accept::new($this);
    }

    protected function newAuthorization() : Authorization\Scheme
    {
        return Authorization\Factory::new($this);
    }

    protected function newContent(mixed $content) : Content
    {
        return Content::new($this, $content);
    }

    protected function newForwarded() : array
    {
        return Forwarded::newArray($this);
    }

    protected function newHeaders() : array
    {
        $headers = [];

        // headers prefixed with HTTP_*
        foreach ($this->server ?? [] as $key => $val) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                $key = str_replace('_', '-', strtolower($key));
                $headers[$key] = (string) $val;
            }
        }

        // RFC 3875 headers not prefixed with HTTP_*
        if (isset($this->server['CONTENT_LENGTH'])) {
            $headers['content-length'] = (string) $this->server['CONTENT_LENGTH'];
        }

        if (isset($this->server['CONTENT_TYPE'])) {
            $headers['content-type'] = (string) $this->server['CONTENT_TYPE'];
        }

        return $headers;
    }

    final protected function newGlobal(mixed $value) : array
    {
        if (! is_array($value)) {
            return [];
        }

        foreach ($value as $key => $val) {
            $value[$key] = $this->immutable($val);
        }

        return $value;
    }

    final protected function immutable(mixed $value) : mixed
    {
        if (is_null($value) || is_scalar($value)) {
            return $value;
        }

        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = $this->immutable($val);
            }
            return $value;
        }

        throw new Exception(
            "Immutable values must be null, scalar, or array."
        );
    }

    protected function newMethod(?string $method) : Method
    {
        return Method::new($this, $method);
    }

    protected function newUploads() : array
    {
        return Upload::newArray($this);
    }

    protected function newUrl(?array $url) : Url
    {
        return Url::new($this, $url);
    }

    protected function newXForwarded() : XForwarded
    {
        return XForwarded::new($this);
    }
}
