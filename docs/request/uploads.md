# Uploads

The _Request_ `$uploads` property is an array of _Sapien\Request\Upload_ objects.

Each _Upload_ object is composed of these public readonly properties:

- `?string $name`: The original name of the file on the client machine.

- `?string $fullPath`: The original path of the file on the client machine.

- `?string $type`: The mime type of the file, if the client provided this
  information.

- `?int $size`: The size, in bytes, of the uploaded file.

- `?string $tmpName`: The temporary filename of the file in which the uploaded
  file was stored on the server.

- `?int $error`: The [error code](https://www.php.net/manual/en/features.file-upload.errors.php)
  associated with this file upload.

These values are derived from the _Request_ `$files` array.

In addition, each _Upload_ object has this public method:

- `move(string $destination) : bool`: The equivalent of
  [`move_uploaded_file`](https://www.php.net/move_uploaded_file).

## Motivation

The _Request_ `$files` property is an identical copy of `$_FILES`. Normally,
`$_FILES` looks like this with multi-file uploads:

```php
// $_FILES ...
[
    'images' => [
        'name' => [
            0 => 'image1.png',
            1 => 'image2.gif',
            2 => 'image3.jpg',
        ],
        'full_path' => [
            0 => 'image1.png',
            1 => 'image2.gif',
            2 => 'image3.jpg',
        ],
        'type' => [
            0 => 'image/png',
            1 => 'image/gif',
            2 => 'image/jpeg',
        ],
        'tmp_name' [
            0 => '/tmp/path/phpABCDEF',
            1 => '/tmp/path/phpGHIJKL',
            2 => '/tmp/path/phpMNOPQR',
        ],
        'error' => [
            0 => 0,
            1 => 0,
            2 => 0,
        ],
        'size' =>[
            0 => 123456,
            1 => 234567,
            2 => 345678,
        ],
    ],
];
```

However, that structure is surprising when we are used to working with `$_POST`.

Therefore, the _Request_ `$uploads` property restructures the data in
`$_FILES` to look like `$_POST` does ...

```php
// $request->uploads in transition ...
[
    'images' => [
        0 => [
            'name' => 'image1.png',
            'full_path' => 'image1.png',
            'type' => 'image/png',
            'tmp_name' => '/tmp/path/phpABCDEF',
            'error' => 0,
            'size' => 123456,
        ],
        1 => [
            'name' => 'image2.gif',
            'full_path' => 'image2.gif',
            'type' => 'image/gif',
            'tmp_name' => '/tmp/path/phpGHIJKL',
            'error' => 0,
            'size' => 234567,
        ],
        2 => [
            'name' => 'image3.jpg',
            'full_path' => 'image3.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => '/tmp/path/phpMNOPQR',
            'error' => 0,
            'size' => 345678,
        ],
    ],
];
```

... and then replaces each array-based descriptor with an _Upload_ instance.
