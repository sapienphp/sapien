<?php
declare(strict_types=1);

namespace Sapien\Request;

use Sapien\Request;
use Sapien\ValueCollection;

/**
 * @phpstan-type FilesArray mixed[]
 *
 * @phpstan-type FilesArrayNested array{
 *    name:string[],
 *    full_path:string[],
 *    type:string[],
 *    size:string[],
 *    tmp_name:string[],
 *    error:string[]
 * }
 *
 * @phpstan-type FileArray array{
 *    name:string,
 *    full_path:string,
 *    type:string,
 *    size:string,
 *    tmp_name:string,
 *    error:string
 * }
 *
 * @method Upload|UploadCollection[] offsetGet(mixed $key)
 */
class UploadCollection extends ValueCollection
{
    public static function new(Request $request) : static
    {
        if (empty($request->files)) {
            return new static();
        }

        return static::newFromFiles($request->files);
    }

    /**
     * @param FilesArray $files
     */
    public static function newFromFiles(array $files) : static
    {
        $items = [];

        /** @var FilesArray|FilesArrayNested|FileArray $file */
        foreach ($files as $key => $file) {
            $items[$key] = static::newFromFile($file);
        }

        return new static($items);
    }

    /**
     * @param FilesArray|FilesArrayNested|FileArray $file
     */
    protected static function newFromFile(array $file) : static|Upload
    {
        if (is_array($file['tmp_name'] ?? null)) {
            /** @var FilesArrayNested $file */
            return static::newFromNested($file);
        }

        if (! is_string($file['tmp_name'] ?? null)) {
            /** @var FilesArray $file */
            return static::newFromFiles($file);
        }

        /** @var FileArray $file */
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
     * @param FilesArrayNested $nested
     */
    protected static function newFromNested(array $nested) : static
    {
        $items = [];
        $keys = array_keys((array) $nested['tmp_name']);

        foreach ($keys as $key) {
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
