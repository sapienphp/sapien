<?php
declare(strict_types=1);

namespace Sapien\Request\Header\Accept;

use Sapien\ValueObject;

class Language extends ValueObject
{
    public function __construct(
        public readonly string $value,
        public readonly string $type,
        public readonly ?string $subtype,
        public readonly string $quality,
        public readonly array $params,
    ) {
    }
}
