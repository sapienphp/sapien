<?php
declare(strict_types=1);

namespace Sapien\Request;

use Sapien\Request;
use Sapien\ValueObject;

class Upload extends ValueObject
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $fullPath,
        public readonly ?string $type,
        public readonly ?int $size,
        public readonly ?string $tmpName,
        public readonly ?int $error,
    ) {
    }

    final public function move(string $destination) : bool
    {
        return move_uploaded_file((string) $this->tmpName, $destination);
    }
}
