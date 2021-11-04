# Forward

The _Request_ object has two readonly properties related to forwarding.

## `$xForwarded`

The `$xForwarded` property is an instance of _Sapien\Request\XForwarded_.

The _XForwarded_ object has these readonly properties:

- `array $for`: An array computed from treating `$header['x-forwarded-for']` as
  comma-separated values.

- `?string $host`: The `$headers['x-forwarded-host']` value, if any.

- `?int $port`: The `$headers['x-forwarded-port']` value, if any.

- `?string $prefix`: The `$headers['x-forwarded-prefix']` value, if any.

- `?string $proto`: The `$headers['x-forwarded-proto']` value, if any.


## `$forwarded`

The `$forwarded` property is a readonly array of _Sapien\Request\Forwarded_
objects.

Each _Forwarded_ object has the following readonly properties computed from the
`$headers['forwarded']` element:

- `?string $by`: The interface where the request came in to the proxy server.

- `?string $for`: Discloses information about the client that initiated the request.

- `?string $host`:  The original value of the Host header field.

- `?string $proto`: The value of the used protocol type.

> **Note:**
>
> Cf. the [Forwarded HTTP Extension](https://tools.ietf.org/html/rfc7239).
