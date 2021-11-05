<?php
declare(strict_types=1);

namespace Sapien\Request\Authorization;

class None extends Scheme
{
    public readonly ?string $scheme;

    public function __construct()
    {
        $this->scheme = null;
    }
}
