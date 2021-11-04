<?php
declare(strict_types=1);

namespace Sapien\Request;

use Sapien\Request;
use Sapien\ValueObject;

class Upload extends ValueObject
{
    static public function newArray(Request $request) : array
    {
        if (empty($request->files)) {
            return [];
        }

        $uploads = [];

        foreach ($request->files as $key => $file) {
            $uploads[$key] = static::newFromFile($file);
        }

        return $uploads;
    }

    static protected function newFromFile(array $file) : static|array
    {
        if (is_array($file['tmp_name'])) {
            return static::newFromNested($file);
        }

        return new static(
            $file['name'] ?? null,
            $file['full_path'] ?? null,
            $file['type'] ?? null,
            $file['size'] ?? null,
            $file['tmp_name'] ?? null,
            $file['error'] ?? null
        );
    }

    static protected function newFromNested(array $nested) : array
    {
        $uploads = [];
        $keys = array_keys($nested['tmp_name']);

        foreach ($keys as $key) {
            $uploads[$key] = static::newFromFile([
                'name' => $nested['name'][$key] ?? null,
                'full_path' => $nested['full_path'][$key] ?? null,
                'type' => $nested['type'][$key] ?? null,
                'size' => $nested['size'][$key] ?? null,
                'tmp_name' => $nested['tmp_name'][$key] ?? null,
                'error' => $nested['error'][$key] ?? null,
            ]);
        }

        return $uploads;
    }

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
