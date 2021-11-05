<?php
declare(strict_types=1);

namespace Sapien\Request\Header;

use Sapien\Request;
use Sapien\ValueObject;

class Forwarded extends ValueObject
{
    static public function newArray(Request $request) : array
    {
        $header = $request->headers['forwarded'] ?? null;

        if ($header === null) {
            return [];
        }

        $proxies = [];
        $forwards = explode(',', $header);

        foreach ($forwards as $forward) {
            $proxies[] = static::new($forward);
        }

        return $proxies;
    }

    static protected function new(string $string) : static
    {
        $forward = [];
        $parts = explode(';', $string);

        foreach ($parts as $part) {
            if (strpos($part, '=') === false) {
                // malformed
                continue;
            }

            list($key, $val) = explode('=', $part);
            $key = strtolower(trim($key));
            $val = trim($val, '\t\n\r\v\"\''); // spaces and quotes
            $forward[$key] = $val;
        }

        return new static(...$forward);
    }

    public function __construct(
        public readonly ?string $by = null,
        public readonly ?string $for = null,
        public readonly ?string $host = null,
        public readonly ?string $proto = null,
    ) {
    }
}
