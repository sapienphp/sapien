# Headers

The header field labels are retained internally in lower-case. This is to
[comply with HTTP/2 requirements](https://tools.ietf.org/html/rfc7540#section-8.1.2);
while HTTP/1.x has no such requirement, lower-case is also recognized as valid.

Further, the header field values are retained and returned
as _Sapien\Request\Header_ objects, not strings.

## Setting

### Setting One Header

`final public setHeader(string $label, Header|string $value) : static`

Overwrites the `$label` HTTP header in the _Response_; a buffered equivalent of
`header("$label: $value", true)`.

The method is fluent, allowing you to chain a call to another _Response_ method.

### Setting All Headers

`final public setHeaders(array $headers) : static`

Overwrites all previous headers on the _Response_, replacing them with the
new `$headers` array. Each `$headers` element key is the field label, and
the corresponding element value is the field value.

The method is fluent, allowing you to chain a call to another _Response_ method.

## Adding

`final public addHeader(string $label, Header|string $value) : static`

Appends to the `$label` HTTP header in the _Response_, comma-separating it from
the existing value; a buffered equivalent of `header("$label: $value", false)`.

The method is fluent, allowing you to chain a call to another _Response_ method.

## Getting

### Getting One Header

`final public getHeader(string $label) : ?Header`

Returns the `$label` header from the _Response_, if it exists.

### Getting All Headers

`final public getHeaders() : array`

Returns the array of _Header_ objects in the _Response_.

## Checking

`final public hasHeader(string $label) : bool`

Returns `true` if the `$label` header exists in the _Response_, `false` if not.

## Removing

### Removing One Header

`final public unsetHeader(string $label) : static`

Removes the `$label` header from the _Response_.

The method is fluent, allowing you to chain a call to another _Response_ method.

### Removing All Headers

`final public unsetHeaders() : static`

Removes all headers from the buffer.

The method is fluent, allowing you to chain a call to another _Response_ method.
