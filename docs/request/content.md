# Content

The _Request_ object `$content` property is a _Sapien\Request\Content_ object.

The _Content_ object has these readonly properties:

- `?string $body`: The content body; see below for the value of this property.

- `?string $charset`: The `charset` parameter value of `$headers['content-type']`, if any.

- `?int $length`: The value of `$headers['content-length']`, if any.

- `?string $md5`: The value of `$headers['content-md5']`, if any.

- `?string $type`: The value of `$headers['content-type']`, if any, minus any parameters.

When the `$body` property is null, _Content_ will read from `php://input`
instead:

```php
$request = new Request();
$body = $request->content->body; // returns `file_get_contents('php://input')`
```

If you want to provide a custom content body string instead, pass it as a
_Request_ argument ...

```php
$request = new Request(
    content: 'custom-php-input-string'
);
```

... or pass an entire _Content_ object of your own construction:

```php
$body = 'custom-php-input-string';
$request = new Request(
    content: new Request\Content(
        body: $body,
        length: strlen($body),
        type: 'text/plain',
        charset: 'utf-8',
        md5: md5($body),
    )
);
```

Note that the `$headers` values are not modified when you pass in custom content
bodies or objects.
