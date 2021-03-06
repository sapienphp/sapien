<?php
declare(strict_types=1);

namespace Sapien\Request\Header\Authorization\Scheme;

use Sapien\Request\Header\Authorization\Scheme;

class Bearer extends Scheme
{
    public function __construct(public string $token)
    {
        parent::__construct();
    }
}
