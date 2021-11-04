# Protocol Version

## Setting the Version

`final public setVersion(?string $version) : static`

This sets the protocol version for the _Response_ (typically '1.0', '1.1', or '2').

The method is fluent, allowing you to chain a call to another _Response_ method.

## Getting the Version

`final public getVersion() : ?string`

Returns the protocol version for the response.
