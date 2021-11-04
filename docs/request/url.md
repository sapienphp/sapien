# URL

The _Request_ `$url` property is an instance of a _Url_ object.

Each _Url_ object has these properties:

- `?string $scheme`
- `?string $host`
- `?int $port`
- `?string $user`
- `?string $pass`
- `?string $path`
- `?string $query`
- `?string $fragment`

The property values are derived from applying
[`parse_url()`](https://www.php.net/parse_url) to the various _Request_
`$server` elements:

- If `$server['HTTPS'] === 'on'`, the scheme is 'https'; otherwise, it is
  'http'.

- If `$server['HTTP_HOST']` is present, it is used as the host name; otherwise,
  `$server['SERVER_NAME']` is used.

- If a port number is present on the host name, it is used as the port;
  otherwise, `$server['SERVER_PORT']` is used.

- `$server['REQUEST_URI']` is used for the path and query string.

If the parsing attempt fails, all _Url_ properties will be null.

The _Url_ is stringable, and will return the full URL string:

```php
$url = (string) $request->url; // https://example.com/path/etc
```
