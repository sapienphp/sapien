<?php
declare(strict_types=1);

namespace Sapien\Request;

use Sapien\ValueObject;
use Sapien\Request;

class Accept extends ValueObject
{
    static public function new(Request $request) : static
    {
        return new static(
            types: Accept\Type::newArray($request->headers['accept'] ?? null),
            charsets: Accept\Charset::newArray($request->headers['accept-charset'] ?? null),
            encodings: Accept\Encoding::newArray($request->headers['accept-encoding'] ?? null),
            languages: Accept\Language::newArray($request->headers['accept-language'] ?? null),
        );
    }

    public function __construct(
        public readonly array $types,
        public readonly array $charsets,
        public readonly array $encodings,
        public readonly array $languages,
    ) {
    }
}
