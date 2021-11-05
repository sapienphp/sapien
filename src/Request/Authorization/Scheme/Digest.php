<?php
declare(strict_types=1);

namespace Sapien\Request\Authorization\Scheme;

use Sapien\Request\Authorization\Scheme;

class Digest extends Scheme
{
    public readonly ?string $cnonce;

    public readonly ?string $nc;

    public readonly ?string $nonce;

    public readonly ?string $opaque;

    public readonly ?string $qop;

    public readonly ?string $realm;

    public readonly ?string $response;

    public readonly ?string $uri;

    public readonly ?string $userhash;

    public readonly ?string $username;

    public function __construct(string $credentials)
    {
        parent::__construct();

        $default = [
            'cnonce' => null,
            'nc' => null,
            'nonce' => null,
            'opaque' => null,
            'qop' => null,
            'realm' => null,
            'response' => null,
            'uri' => null,
            'userhash' => null,
            'username' => null,
        ];

        preg_match_all(
            '@(\w+)\s*=\s*(?:([\'"])([^\2]+?)\2|([^\s,]+))@',
            $credentials,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $param) {
            $prop = $param[1];
            if (property_exists($this, $prop)) {
                $this->$prop = $param[3] ? $param[3] : $param[4];
                unset($default[$prop]);
            }
        }

        foreach ($default as $prop => $value) {
            $this->$prop = $value;
        }
    }
}
