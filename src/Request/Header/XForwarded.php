<?php
declare(strict_types=1);

namespace Sapien\Request\Header;

use Sapien\Request;
use Sapien\ValueObject;

class XForwarded extends ValueObject
{
    public static function new(Request $request) : static
    {
        return new static(
            for: static::newFor($request),
            proto: static::newProto($request),
            host: static::newHost($request),
            port: static::newPort($request),
            prefix: static::newPrefix($request),
        );
    }

    /**
     * @return array<int, string>
     */
    protected static function newFor(Request $request) : array
    {
        if (! isset($request->headers['x-forwarded-for'])) {
            return [];
        }

        $forwardedFor = [];
        $ips = explode(',', $request->headers['x-forwarded-for']);

        foreach ($ips as $ip) {
            $forwardedFor[] = trim($ip);
        }

        return $forwardedFor;
    }

    protected static function newHost(Request $request) : ?string
    {
        if (! isset($request->headers['x-forwarded-host'])) {
            return null;
        }

        return trim($request->headers['x-forwarded-host']);
    }

    protected static function newProto(Request $request) : ?string
    {
        if (! isset($request->headers['x-forwarded-proto'])) {
            return null;
        }

        return trim($request->headers['x-forwarded-proto']);
    }

    protected static function newPort(Request $request) : ?int
    {
        if (! isset($request->headers['x-forwarded-port'])) {
            return null;
        }

        $port = null;
        $value = trim($request->headers['x-forwarded-port']);
        $noint = trim($value, '01234567890');

        if ($noint === '') {
            $port = (int) $value;
        }

        return $port;
    }

    protected static function newPrefix(Request $request) : ?string
    {
        if (! isset($request->headers['x-forwarded-prefix'])) {
            return null;
        }

        return trim($request->headers['x-forwarded-prefix']);
    }

    /**
     * @param array<int, string> $for
     */
    public function __construct(
        public readonly ?array $for,
        public readonly ?string $proto,
        public readonly ?string $host,
        public readonly ?int $port,
        public readonly ?string $prefix,
    ) {
    }
}
