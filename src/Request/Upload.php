<?php
declare(strict_types=1);

namespace Sapien\Request;

use Sapien\Request;
use Sapien\ValueObject;

class Upload extends ValueObject
{
    public readonly ?string $name;

    public readonly ?string $fullPath;

    public readonly ?string $type;

    public readonly ?int $size;

    public readonly ?string $tmpName;

    public readonly ?int $error;

    public function __construct(
        ?string $name,
        ?string $fullPath,
        ?string $type,
        int|string|null $size,
        ?string $tmpName,
        int|string|null $error,
    ) {
        $this->name = $name;
        $this->fullPath = $fullPath;
        $this->type = $type;
        $this->size = $size === null ? null : (int) $size;
        $this->tmpName = $tmpName;
        $this->error = $error === null ? null : (int) $error;
    }

    final public function move(string $destination) : bool
    {
        return move_uploaded_file((string) $this->tmpName, $destination);
    }
}
