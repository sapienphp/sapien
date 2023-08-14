<?php
declare(strict_types=1);

namespace Sapien\Request;

use Sapien\Request;
use Sapien\ValueCollection;

/**
 * @phpstan-type FileArray array{
 *    name:string,
 *    full_path:string,
 *    type:string,
 *    size:string,
 *    tmp_name:string|mixed[],
 *    error:string
 * }
 * @method Upload|UploadCollection[] offsetGet(mixed $key)
 */
class UploadCollection extends ValueCollection
{
    public static function new(Request $request) : static
    {
        if (empty($request->files)) {
            return new static();
        }

        $items = [];

        /** @var FileArray $file */
        foreach ($request->files as $key => $file) {
            $items[$key] = static::newFromFile($file);
        }

        return new static($items);
    }

    /**
     * @param FileArray $file
     */
    protected static function newFromFile(array $file) : static|Upload
    {
        if (is_array($file['tmp_name'])) {
            return static::newFromNested($file);
        }

        return new Upload(
            $file['name'],
            $file['full_path'],
            $file['type'],
            $file['size'],
            $file['tmp_name'],
            $file['error'],
        );
    }

    /**
     * @param mixed[] $nested
     */
    protected static function newFromNested(array $nested) : static
    {
        $items = [];
        $keys = array_keys((array) $nested['tmp_name']);

        foreach ($keys as $key) {
            /** @var FileArray $file */
            $file = [
                'name' => $nested['name'][$key] ?? null,
                'full_path' => $nested['full_path'][$key] ?? null,
                'type' => $nested['type'][$key] ?? null,
                'size' => $nested['size'][$key] ?? null,
                'tmp_name' => $nested['tmp_name'][$key] ?? null,
                'error' => $nested['error'][$key] ?? null,
            ];
            $items[$key] = static::newFromFile($file);
        }

        return new static($items);
    }
}
