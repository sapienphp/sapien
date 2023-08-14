<?php
declare(strict_types=1);

namespace Sapien\Request\Header;

use Sapien\Request;
use Sapien\ValueCollection;

class ForwardedCollection extends ValueCollection
{
    public static function new(Request $request) : static
    {
        $header = $request->headers['forwarded'] ?? null;

        if ($header === null) {
            return new static();
        }

        $items = [];
        $forwards = explode(',', $header);

        foreach ($forwards as $forward) {
            $items[] = Forwarded::new($forward);
        }

        return new static($items);
    }
}
