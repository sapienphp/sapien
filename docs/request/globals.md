# Globals

The _Request_ object presents these readonly properties as copies of the PHP
superglobals:

- `array $cookies`: A copy of `$_COOKIE`.

- `array $files`: A copy of `$_FILES`.

- `array $input`: A copy of `$_POST`, *or* a `json_decode()`d array from the
  content body (see below).

- `array $query`: A copy of `$_GET`.

- `array $server`: A copy of `$_SERVER`.

You can work with them the same as you would with any readonly array:

```php
// get the `?q=` value, defaulting to an empty string
$searchTerm = $request->query['q'] ?? '';
```

## JSON Decoding

The `$_POST` superglobal is populated by PHP when it can decode the content
body as `application/x-www-form-urlencoded` or `multipart/form-data`.
However, it is often the case that content bodies are JSON encoded instead.

Thus, as a convenience, if the _Request_ `content-type` is `application/json`,
then `$request->input` will be an array computed by applying `json_decode()` to
the content body.

## Custom Values

You can provide alternative or custom values via the `$globals` constructor
parameter:

```php
$request = new Request(
    globals: [
        '_COOKIE' => [...],
        '_FILES' => [...],
        '_GET' => [...],
        '_POST' => [...],
        '_SERVER' => [...],
    ]
);
```

Any values not present in the `$globals` constructor parameter will be provided
by the existing superglobal.
