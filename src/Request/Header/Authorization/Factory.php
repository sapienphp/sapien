<?php
declare(strict_types=1);

namespace Sapien\Request\Header\Authorization;

use Sapien\Request;

class Factory
{
    public static function new(Request $request) : Scheme
    {
        $header = $request->headers['authorization'] ?? null;

        if ($header === null) {
            return new None();
        }

        $pos = strpos($header, ' ');

        if ($pos === false) {
            return new None();
        }

        $scheme = trim(substr($header, 0, $pos));
        $credentials = trim(substr($header, $pos + 1));

        switch (strtolower($scheme)) {
            case 'basic':
                return new Scheme\Basic($credentials);

            case 'digest':
                return new Scheme\Digest($credentials);

            case 'bearer':
                return new Scheme\Bearer($credentials);

            default:
                return new Generic($scheme, $credentials);
        }
    }
}
