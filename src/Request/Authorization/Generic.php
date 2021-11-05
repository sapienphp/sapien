<?php
declare(strict_types=1);

namespace Sapien\Request\Authorization;

class Generic extends Scheme
{
    public function __construct(
        public readonly ?string $scheme,
        public readonly string $credentials,
    ) {
    }
}
