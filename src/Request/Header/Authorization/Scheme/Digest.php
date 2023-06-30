<?php
declare(strict_types=1);

namespace Sapien\Request\Header\Authorization\Scheme;

use Sapien\Request\Header\Authorization\Scheme;

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

        if ($args['nc'] !== null) {
            $args['nc'] = ctype_digit($args['nc']) ? (int) $args['nc'] : null;
        }

        if ($args['userhash'] !== null) {
            $args['userhash'] = (strtolower($args['userhash']) === 'true');
        }

        $this->cnonce = $args['cnonce'];
        $this->nc = $args['nc'];
        $this->nonce = $args['nonce'];
        $this->opaque = $args['opaque'];
        $this->qop = $args['qop'];
        $this->realm = $args['realm'];
        $this->response = $args['response'];
        $this->uri = $args['uri'];
        $this->userhash = $args['userhash'];
        $this->username = $args['username'];
    }
}
