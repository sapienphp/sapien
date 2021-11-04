# Globals

The _Request_ object presents these readonly properties as copies of the PHP
superglobals:

- `array $cookies`: A copy of `$_COOKIE`.

- `array $files`: A copy of `$_FILES`.

- `array $input`: A copy of `$_POST`.

- `array $query`: A copy of `$_GET`.

- `array $server`: A copy of `$_SERVER`.

You can work with them the same as you would any array:

```php
// get the `?q=` value, defaulting to an empty string
$searchTerm = $request->query['q'] ?? '';
```

You can provide alternative or custom values via the `$globals` constructor
parameter:

```php
$request = new Request(
    globals: [
        '_COOKIES' => [...],
        '_FILES' => [...],
        '_INPUT' => [...],
        '_QUERY' => [...],
        '_SERVER' => [...],
    ]
);
```

Any values not present in the `$globals` constructor parameter will be provided
by the existing superglobal.
