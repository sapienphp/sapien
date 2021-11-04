# Status Code

## Setting the Code

`final public setCode(?int $code) : static`

Sets the status code for the _Response_; a buffered equivalent of
`http_response_code($code)`.

The method is fluent, allowing you to chain a call to another _Response_ method.


## Getting the Code

`final public getCode() : ?int`

Returns the the status code for the response.
