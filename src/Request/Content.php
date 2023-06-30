<?php
declare(strict_types=1);

namespace Sapien\Request;

use Sapien\Request;
use Sapien\ValueObject;

/**
 * @property-read string $body
 */
class Content extends ValueObject
{
    static public function new(Request $request, mixed $body) : static
    {
        return new static(
            body: $body,
            charset: static::newCharset($request),
            length: static::newLength($request),
            md5: static::newMd5($request),
            type: static::newType($request),
        );
    }

    static protected function newCharset(Request $request) : ?string
    {
        if (! isset($request->headers['content-type'])) {
            return null;
        }

        $parts = explode(';', $request->headers['content-type']);
        array_shift($parts);
        if (empty($parts)) {
            return null;
        }

        foreach ($parts as $part) {
            $part = str_replace(' ', '', $part);
            if (substr($part, 0, 8) === 'charset=') {
                return $contentCharset = trim(substr($part, 8));
            }
        }

        return null;
    }

    static protected function newLength(Request $request) : ?int
    {
        if (! isset($request->headers['content-length'])) {
            return null;
        }

        $contentLength = null;
        $value = trim($request->headers['content-length']);
        $noint = trim($value, '01234567890');

        if ($noint === '') {
            $contentLength = (int) $value;
        }

        return $contentLength;
    }

    static protected function newMd5(Request $request) : ?string
    {
        if (! isset($request->headers['content-md5'])) {
            return null;
        }

        return trim($request->headers['content-md5']);
    }

    static protected function newType(Request $request) : ?string
    {
        if (! isset($request->headers['content-type'])) {
            return null;
        }

        $parts = explode(';', $request->headers['content-type']);
        $contentType = null;
        $type = array_shift($parts);
        $regex = "/^[!#$%&'*+.^_`|~0-9A-Za-z-]+\/[!#$%&'*+.^_`|~0-9A-Za-z-]+$/";

        if (preg_match($regex, $type) === 1) {
            $contentType = $type;
        }

        return $contentType;
    }

    public function __construct(
        private readonly ?string $body = null,
        public readonly ?string $charset = null,
        public readonly ?int $length = null,
        public readonly ?string $md5 = null,
        public readonly ?string $type = null,
    ) {
    }

    public function __get(string $key) : mixed
    {
        if ($key === 'body') {
            return $this->getBody();
        }

        return parent::__get($key);
    }

    protected function getBody() : string
    {
        return $this->body ?? (string) file_get_contents('php://input');
    }

    /**
     * @return mixed[]
     */
    public function getParsedBody() : ?array
    {
        if (strtolower($this->type ?? '') !== 'application/json') {
            return null;
        }

        $result = json_decode(
            $this->getBody(),
            true,
            512,
            JSON_BIGINT_AS_STRING
        );

        return is_array($result) ? $result : null;
    }
}
