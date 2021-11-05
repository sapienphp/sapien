<?php
declare(strict_types=1);

namespace Sapien\Request\Header\Authorization\Scheme;

use Sapien\Request\Header\Authorization\Scheme;

class Basic extends Scheme
{
    public readonly string $username;

    public readonly string $password;

    public function __construct(string $credentials)
    {
        parent::__construct();
        $decoded = base64_decode($credentials);
        $pos = strpos($decoded, ':');
        $this->username = substr($decoded, 0, $pos);
        $this->password = substr($decoded, $pos + 1);
    }
}
