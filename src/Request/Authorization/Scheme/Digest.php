<?php
declare(strict_types=1);

namespace Sapien\Request\Authorization\Scheme;

use Sapien\Request\Authorization\Scheme;

class Digest extends Scheme
{
    public readonly ?string $cnonce;

    public readonly ?int $nc;

    public readonly ?string $nonce;

    public readonly ?string $opaque;

    public readonly ?string $qop;

    public readonly ?string $realm;

    public readonly ?string $response;

    public readonly ?string $uri;

    public readonly ?bool $userhash;

    public readonly ?string $username;

    public function __construct(string $credentials)
    {
        parent::__construct();

        $args = [
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
            $key = $param[1];
            if (array_key_exists($key, $args)) {
                $args[$key] = $param[3] ? $param[3] : $param[4];
            }
        }

        if (isset($args['nc'])) {
            $args['nc'] = ctype_digit($args['nc']) ? (int) $args['nc'] : null;
        }

        if (isset($args['userhash'])) {
            $args['userhash'] = (strtolower($args['userhash']) === 'true');
        }

        foreach ($args as $key => $val) {
            $this->$key = $val;
        }
    }
}
