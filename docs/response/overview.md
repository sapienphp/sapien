# Overview

The Sapien _Response_ is a mutable object representing the PHP response to be
sent from the server.

It provides a retention space for the HTTP response version, code,
headers, cookies, and content, so they can be inspected before sending.

Use a _Response_ in place of the `header()`, `setcookie()`, `setrawcookie()`,
etc. functions.

## Instantiation

Instantation is straightforward:

```php
use Sapien\Response;

$response = new Response();
```

## Examples

Here are some basic examples of creating and sending a response:

```php
// a "200 OK" response with some body content:
$response
    ->setHeader('content-type', 'text/plain')
    ->setContent('Hello World!')
    ->send();

// a "303 See Other" response
$response
    ->setCode(303)
    ->setHeader('location', '/path/to/resource')
    ->send();


// sending a cookie with the response; note how the setter methods
// are fluent, allowing you to chain calls to the Response
$response
    ->setCookie(name: 'foo', value: 'bar')
    ->setContent("Cookie has been set!")
    ->send();
```

## Further Reading

The _Response_ provides public methods related to these areas:

- [protocol version](./version.md)
- [status code](./code.md)
- [headers](./headers.md)
- [cookies](./cookies.md)
- [header callbacks](./callbacks.md)
- [content](./content)
- [sending the response](./sending.md)

You can [extend the _Response_](./extending.md) for your own purposes, though
you should check out one of the [specialized responses](./special.md) before
doing so.
