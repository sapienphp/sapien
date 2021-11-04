<?php
declare(strict_types=1);

namespace Sapien\Request;

use Sapien\ValueObject;
use Sapien\Request;
use Stringable;

class Method extends ValueObject implements Stringable
{
    static public function new(Request $request, ?string $name) : static
    {
        if ($name !== null) {
            return new static($name);
        }

        $server = $request->server;

        if (empty($server)) {
            return new static();
        }

        $name = null;

        if (isset($server['REQUEST_METHOD'])) {
            $name = $server['REQUEST_METHOD'];
        }

        if (
            $name === 'POST'
            && isset($server['HTTP_X_HTTP_METHOD_OVERRIDE'])
        ) {
            $name = $server['HTTP_X_HTTP_METHOD_OVERRIDE'];
        }

        if ($name === null) {
            return new static();
        }

        return new static($name);
    }

    public readonly ?string $name;

    public function __construct(?string $name = null)
    {
        if (is_string($name)) {
            $name = strtoupper($name);
        }

        $this->name = $name;
    }

    public function __toString() : string
    {
        return (string) $this->name;
    }

    public function is(string $name) : bool
    {
        return $this->name === strtoupper($name);
    }
}
