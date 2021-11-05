# Overview

The Sapien _Request_ is a value object, composed of other value objects,
representing the PHP request received by the server. Use a _Request_ instead of
the various PHP superglobals.

## Instantiation

Instantiation of _Request_ is straightforward:

```php
use Sapien\Request;

$request = new Request();
```

## Further Reading

The _Request_ provides public readonly properties related to these areas:

- [globals](./globals.md)
- [file uploads](./uploads.md)
- [request method](./method.md)
- [request url](./url.md)
- [headers](./headers.md)
    - [`accept*`](./accept.md)
    - [`authorization`](./authorization.md)
    - [`forwarded` and `x-forwarded`](./forward.md)
- [content](./content)

You can also [extend the _Request_](./extending.md) for your own purposes.
