<?php
declare(strict_types=1);

namespace Sapien\Request\Header\Authorization;

use Sapien\Request;

abstract class Scheme
{
    public readonly ?string $scheme;

    public function __construct()
    {
        $class = get_class($this);
        $parts = explode('\\', $class);
        $this->scheme = end($parts);
    }

    public function is(?string $scheme) : bool
    {
        return strtolower($this->scheme ?? '') === strtolower($scheme ?? '');
    }
}
