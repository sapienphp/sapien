<?php
declare(strict_types=1);

namespace Sapien\Request\Header\Accept;

use Sapien\ValueObject;

class Charset extends ValueObject
{
    /**
     * @param mixed[] $params
     */
    public function __construct(
        public readonly string $value,
        public readonly string $quality,
        public readonly array $params,
    ) {
    }
}
