# Headers

The _Request_ `$headers` property is a readonly array derived from various
_Request_ `$server` property values.

Each `$server['HTTP_*']` element will be represented in `$headers` using a
lower-kebab-cased key, along with the `$server['CONTENT_LENGTH']` and
`$server['CONTENT_TYPE']` values.

```php
$_SERVER = [
    'HTTP_HOST' => 'example.com',
    'HTTP_FOO_BAR_BAZ' => 'dib,zim,gir',
    'CONTENT_LENGTH' => '123',
    'CONTENT_TYPE' => 'text/plain',
];

$request = new Request();

assert($request->headers['host'] === $_SERVER['HTTP_HOST']);
assert($request->headers['foo-bar-baz'] === 'dib,zim,gir');
assert($request->headers['content-length'] === '123');
assert($request->headers['content-type'] === 'text/plain');
```

You can work with `$headers` as you would with any readonly array:

```php
$fooBarBaz = $request->headers['foo-bar-baz'] ?? null;
```
