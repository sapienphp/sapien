<?php
declare(strict_types=1);

namespace Sapien;

use Sapien\Response\Cookie;
use Sapien\Response\Header;
use SplFileObject;
use Stringable;

class Response
{
    private ?string $version = null;

    private ?int $code = null;

    /**
     * @var array<string, Header>
     */
    private array $headers = [];

    /**
     * @var array<string, Cookie>
     */
    private array $cookies = [];

    private mixed $content = null;

    /**
     * @var callable[]
     */
    private array $headerCallbacks = [];

    final public function setVersion(?string $version) : static
    {
        $this->version = $version;
        return $this;
    }

    final public function getVersion() : ?string
    {
        return $this->version;
    }

    final public function setCode(?int $code) : static
    {
        $this->code = $code;
        return $this;
    }

    final public function getCode() : ?int
    {
        return $this->code;
    }

    final public function setHeader(string $label, Header|string $value) : static
    {
        $label = strtolower(trim($label));

        if ($label === '') {
            throw new Exception('Header label cannot be blank');
        }

        if (is_string($value)) {
            $value = new Header($value);
        }

        $this->headers[$label] = $value;
        return $this;
    }

    final public function addHeader(string $label, Header|string $value) : static
    {
        $label = strtolower(trim($label));

        if ($label === '') {
            throw new Exception('Header label cannot be blank');
        }

        if (is_string($value)) {
            $value = new Header($value);
        }

        if (! isset($this->headers[$label])) {
            $this->headers[$label] = $value;
        } else {
            $this->headers[$label]->add($value);
        }

        return $this;
    }

    final public function unsetHeader(string $label) : static
    {
        $label = strtolower(trim($label));
        unset($this->headers[$label]);
        return $this;
    }

    /**
     * @param array<string, Header|string> $headers
     */
    final public function setHeaders(array $headers) : static
    {
        $this->headers = [];

        foreach ($headers as $label => $value) {
            $this->setHeader($label, $value);
        }

        return $this;
    }

    final public function unsetHeaders() : static
    {
        $this->headers = [];
        return $this;
    }

    /**
     * @return array<string, Header>
     */
    final public function getHeaders() : array
    {
        return $this->headers;
    }

    final public function getHeader(string $label) : ?Header
    {
        $label = strtolower(trim($label));
        return $this->headers[$label] ?? null;
    }

    final public function hasHeader(string $label) : bool
    {
        $label = strtolower(trim($label));
        return isset($this->headers[$label]);
    }

    final public function setCookie(
        string $name,
        string|Cookie $value = '',
        int $expires = null,
        string $path = null,
        string $domain = null,
        bool $secure = null,
        bool $httponly = null,
        string $samesite = null
    ) : static
    {
        if ($value instanceof Cookie) {
            $this->cookies[$name] = $value;
            return $this;
        }

        $this->cookies[$name] = new Cookie(
            'setcookie',
            $value,
            [
                'expires' => $expires,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => $httponly,
                'samesite' => $samesite,
            ]
        );

        return $this;
    }

    final public function setRawCookie(
        string $name,
        string $value = '',
        int $expires = null,
        string $path = null,
        string $domain = null,
        bool $secure = null,
        bool $httponly = null,
        string $samesite = null
    ) : static
    {
        $this->cookies[$name] = new Cookie(
            'setrawcookie',
            $value,
            [
                'expires' => $expires,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => $httponly,
                'samesite' => $samesite,
            ]
        );

        return $this;
    }

    final public function unsetCookie(string $name) : static
    {
        unset($this->cookies[$name]);
        return $this;
    }

    final public function unsetCookies() : static
    {
        $this->cookies = [];
        return $this;
    }

    /**
     * @return array<string, Cookie>
     */
    final public function getCookies() : array
    {
        return $this->cookies;
    }

    /**
     * @param array<string, string|Cookie> $cookies
     */
    final public function setCookies(array $cookies) : static
    {
        $this->cookies = [];

        foreach ($cookies as $name => $value) {
            $this->setCookie($name, $value);
        }

        return $this;
    }

    final public function getCookie(string $name) : ?Cookie
    {
        return $this->cookies[$name] ?? null;
    }

    final public function hasCookie(string $name) : bool
    {
        return isset($this->cookies[$name]);
    }

    /**
     * @param callable[] $headerCallbacks
     */
    final public function setHeaderCallbacks(array $headerCallbacks) : static
    {
        $this->headerCallbacks = [];

        foreach ($headerCallbacks as $headerCallback) {
            $this->addHeaderCallback($headerCallback);
        }

        return $this;
    }

    final public function addHeaderCallback(callable $headerCallback) : static
    {
        $this->headerCallbacks[] = $headerCallback;
        return $this;
    }

    /**
     * @return callable[]
     */
    final public function getHeaderCallbacks() : array
    {
        return $this->headerCallbacks;
    }

    final public function hasHeaderCallbacks() : bool
    {
        return ! empty($this->headerCallbacks);
    }

    final public function unsetHeaderCallbacks() : static
    {
        $this->headerCallbacks = [];
        return $this;
    }

    final public function getContent() : mixed
    {
        return $this->content;
    }

    public function setContent(mixed $content) : static
    {
        $this->content = $content;
        return $this;
    }

    public function send() : void
    {
        foreach ($this->headerCallbacks as $callback) {
            $callback($this);
        }

        $version = $this->version ?? '1.1';
        $code = $this->code ?? 200;
        header("HTTP/{$version} {$code}", true, $code);

        foreach ($this->headers as $label => $value) {
            header("{$label}: {$value}", false);
        }

        foreach ($this->cookies as $name => $cookie) {
            ($cookie->func)($name, $cookie->value, $cookie->options);
        }

        $this->sendContent();
    }

    protected function sendContent() : void
    {
        if (is_resource($this->content)) {
            rewind($this->content);
            fpassthru($this->content);
            return;
        }

        if ($this->content instanceof SplFileObject) {
            $this->content->rewind();
            $this->content->fpassthru();
            return;
        }

        if (
            is_callable($this->content)
            && ! is_string($this->content)
        ) {
            echo ($this->content)();
            return;
        }

        if (is_iterable($this->content)) {
            foreach ($this->content as $output) {
                echo $output;
            }
            return;
        }

        if (
            is_string($this->content)
            || $this->content instanceof Stringable
        ) {
            echo $this->content;
        }
    }
}
