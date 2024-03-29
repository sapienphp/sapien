<?php
declare(strict_types=1);

namespace Sapien;

use Sapien\Request\Content;
use Sapien\Request\Header\Accept;
use Sapien\Request\Header\Authorization;
use Sapien\Request\Header\ForwardedCollection;
use Sapien\Request\Header\XForwarded;
use Sapien\Request\Method;
use Sapien\Request\UploadCollection;
use Sapien\Request\Url;

/**
 * @phpstan-type stringy int|float|string|null
 * @phpstan-import-type UrlArray from Url
 * @property-read Accept $accept
 * @property-read Authorization\Scheme $authorization
 * @property-read ForwardedCollection $forwarded
 * @property-read XForwarded $xForwarded
 */
class Request extends ValueObject
{
    private readonly Accept $accept;

    private readonly Authorization\Scheme $authorization;

    public readonly Content $content;

    /**
     * @var array<string, string>
     */
    public readonly array $cookies;

    /**
     * @var mixed[]
     */
    public readonly array $files;

    private readonly ForwardedCollection $forwarded;

    /**
     * @var array<string, string>
     */
    public readonly array $headers;

    /**
     * @var mixed[]
     */
    public readonly array $input;

    public readonly Method $method;

    /**
     * @var array<string, string>
     */
    public readonly array $query;

    /**
     * @var array<string, string>
     */
    public readonly array $server;

    public readonly UploadCollection $uploads;

    public readonly Url $url;

    private readonly XForwarded $xForwarded;

    /**
     * @param mixed[] $globals
     * @param UrlArray $url
     */
    public function __construct(
        array $globals = null,
        string $method = null,
        array $url = null,
        Content|string|null $content = null,
    ) {
        /** @var array<string, string> */
        $server = $this->newGlobal($globals['_SERVER'] ?? $_SERVER);
        $this->server = $server;
        $this->headers = $this->newHeaders();
        $this->method = $this->newMethod($method);
        $this->url = $this->newUrl($url);
        $this->content = $this->newContent($content);

        /** @var array<string, string> */
        $cookies = $this->newGlobal($globals['_COOKIE'] ?? $_COOKIE);
        $this->cookies = $cookies;
        $files = $this->newGlobal($globals['_FILES'] ?? $_FILES);
        $this->files = $files;
        $input = $globals['_POST'] ?? $this->content->getParsedBody() ?? $_POST;
        $this->input = $this->newGlobal($input);
        $this->uploads = $this->newUploads();

        /** @var array<string, string> */
        $query = $this->newGlobal($globals['_GET'] ?? $_GET);
        $this->query = $query;
    }

    public function __get(string $key) : mixed
    {
        if (! property_exists($this, $key)) {
            return parent::__get($key);
        }

        if (! isset($this->{$key})) {
            $method = "new{$key}";
            $this->{$key} = $this->{$method}();
        }

        return $this->{$key};
    }

    public function __isset(string $key) : bool
    {
        return property_exists($this, $key);
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

    protected function newForwarded() : ForwardedCollection
    {
        return ForwardedCollection::new($this);
    }

    /**
     * @return array<string, string>
     */
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

    /**
     * @return mixed[]
     */
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

        throw new Exception("Immutable values must be null, scalar, or array.");
    }

    protected function newMethod(?string $method) : Method
    {
        return Method::new($this, $method);
    }

    protected function newUploads() : UploadCollection
    {
        return UploadCollection::new($this);
    }

    /**
     * @param ?UrlArray $url
     */
    protected function newUrl(?array $url) : Url
    {
        return Url::new($this, $url);
    }

    protected function newXForwarded() : XForwarded
    {
        return XForwarded::new($this);
    }
}
