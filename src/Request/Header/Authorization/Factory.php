<?php
declare(strict_types=1);

namespace Sapien\Request\Header\Authorization;

use Sapien\Request;

class Factory
{
    static public function new(Request $request) : Scheme
    {
        $header = $request->headers['authorization'] ?? null;

        if ($header === null) {
            return new None();
        }

        $pos = strpos($header, ' ');
        $scheme = trim(substr($header, 0, $pos));
        $credentials = trim(substr($header, $pos + 1));
        $class = 'Sapien\\Request\\Header\\Authorization\\Scheme\\'
            . ucfirst(strtolower($scheme));

        if (class_exists($class)) {
            return new $class($credentials);
        }

        return new Generic($scheme, $credentials);
    }
}
