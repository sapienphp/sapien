<?php
declare(strict_types=1);

namespace Sapien\Request;

use Sapien\Request;
use Sapien\ValueObject;
use Stringable;

/**
 * @phpstan-type UrlArray array{
 *    scheme?:string,
 *    host?:string,
 *    port?:int,
 *    user?:string,
 *    pass?:string,
 *    path?:string,
 *    query?:string,
 *    fragment?:string
 * }
 */
class Url extends ValueObject implements Stringable
{
    /**
     * @param ?UrlArray $url
     */
    public static function new(Request $request, ?array $url) : static
    {
        if ($url !== null) {
            return new static(...$url);
        }

        $server = $request->server;

        if (empty($server)) {
            return new static();
        }

        $url = [];

        // scheme
        $scheme = 'http://';

        if (isset($server['HTTPS']) && strtolower($server['HTTPS']) == 'on') {
            $scheme = 'https://';
        }

        // host
        if (isset($server['HTTP_HOST'])) {
            $host = $server['HTTP_HOST'];
        } elseif (isset($server['SERVER_NAME'])) {
            $host = $server['SERVER_NAME'];
        } else {
            $host = '___';
        }

        // port
        preg_match('#\\:[0-9]+$#', $host, $matches);

        if ($matches) {
            $host_port = array_pop($matches);
            $host = substr($host, 0, -strlen($host_port));
        }

        $port = isset($server['SERVER_PORT']) ? ':' . $server['SERVER_PORT'] : '';

        if ($port == '' && ! empty($host_port)) {
            $port = $host_port;
        }

        // all else
        $uri = isset($server['REQUEST_URI']) ? $server['REQUEST_URI'] : '';

        if ($host == '___' && $port === '' && $uri === '') {
            return new static();
        }

        $url = $scheme . $host . $port . $uri;
        $base = [
            'scheme' => null,
            'host' => null,
            'port' => null,
            'user' => null,
            'pass' => null,
            'path' => null,
            'query' => null,
            'fragment' => null,
        ];
        $url = array_merge($base, (array) parse_url($url));

        if ($host === '___') {
            $url['host'] = null;
        }

        return new static(...$url);
    }

    public function __construct(
        public readonly ?string $scheme = null,
        public readonly ?string $host = null,
        public readonly ?int $port = null,
        public readonly ?string $user = null,
        public readonly ?string $pass = null,
        public readonly ?string $path = null,
        public readonly ?string $query = null,
        public readonly ?string $fragment = null,
    ) {
    }

    public function __toString() : string
    {
        $info = $this->user;
        $info .= $this->pass ? ":{$this->pass}" : "";
        $info .= $info ? "@" : "";
        $port = $this->port ? ":{$this->port}" : "";
        $query = $this->query ? "?{$this->query}" : "";
        $fragment = $this->fragment ? "#{$this->fragment}" : "";
        return "{$this->scheme}://{$info}{$this->host}{$port}{$this->path}{$query}{$fragment}";
    }
}
