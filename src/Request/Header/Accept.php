<?php
declare(strict_types=1);

namespace Sapien\Request\Header;

use Sapien\ValueObject;
use Sapien\Request;

class Accept extends ValueObject
{
    public static function new(Request $request) : static
    {
        return new static(
            types: Accept\TypeCollection::new($request->headers['accept'] ?? null),
            charsets: Accept\CharsetCollection::new(
                $request->headers['accept-charset'] ?? null,
            ),
            encodings: Accept\EncodingCollection::new(
                $request->headers['accept-encoding'] ?? null,
            ),
            languages: Accept\LanguageCollection::new(
                $request->headers['accept-language'] ?? null,
            ),
        );
    }

    public function __construct(
        public readonly Accept\TypeCollection $types,
        public readonly Accept\CharsetCollection $charsets,
        public readonly Accept\EncodingCollection $encodings,
        public readonly Accept\LanguageCollection $languages,
    ) {
    }
}
