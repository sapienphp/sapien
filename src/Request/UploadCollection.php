<?php
declare(strict_types=1);

namespace Sapien\Request;

use Sapien\Request;
use Sapien\ValueCollection;

class UploadCollection extends ValueCollection
{
    static public function new(Request $request) : static
    {
        if (empty($request->files)) {
            return new static();
        }

        $items = [];

        foreach ($request->files as $key => $file) {
            $items[$key] = static::newFromFile($file);
        }

        return new static($items);
    }

    static protected function newFromFile(array $file) : static|Upload
    {
        if (is_array($file['tmp_name'])) {
            return static::newFromNested($file);
        }

        return new Upload(
            $file['name'] ?? null,
            $file['full_path'] ?? null,
            $file['type'] ?? null,
            $file['size'] ?? null,
            $file['tmp_name'] ?? null,
            $file['error'] ?? null
        );
    }

    static protected function newFromNested(array $nested) : static
    {
        $items = [];
        $keys = array_keys($nested['tmp_name']);

        foreach ($keys as $key) {
            $items[$key] = static::newFromFile([
                'name' => $nested['name'][$key] ?? null,
                'full_path' => $nested['full_path'][$key] ?? null,
                'type' => $nested['type'][$key] ?? null,
                'size' => $nested['size'][$key] ?? null,
                'tmp_name' => $nested['tmp_name'][$key] ?? null,
                'error' => $nested['error'][$key] ?? null,
            ]);
        }

        return new static($items);
    }
}
