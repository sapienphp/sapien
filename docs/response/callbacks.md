# Header Callbacks

## Setting

`final public setHeaderCallbacks(array $callbacks) : static`

Sets an array of callbacks to be invoked just before headers are sent by
the _Response_, replacing any existing callbacks.

This method is similar to
[`header_register_callback()`](https://secure.php.net/header_register_callback),
except that *multiple* callbacks may be registered with the Response.

Each value in the `$callbacks` array is expected to be a callable with the
following signature:

`function (Response $response) : void`

The method is fluent, allowing you to chain a call to another _Response_ method.

## Adding

`final public addHeaderCallback(callable $callback) : static`

Appends one callback to the current array of header callbacks in the _Response_.

The `$callback` is expected to be a callable with the following signature:

`function (Response $response) : void`

The method is fluent, allowing you to chain a call to another _Response_ method.

## Getting

`final public getHeaderCallbacks() : array`

Returns the array of header callbacks in the _Response_.

## Checking

`final public hasHeaderCallbacks() : bool`

Returns `true` if there are any header callbacks in the _Response_, false if not.

## Removing

`final public unsetHeaderCallbacks() : static`

Removes all header callbacks from the _Response_.

The method is fluent, allowing you to chain a call to another _Response_ method.
