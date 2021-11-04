# Content

## Setting

`public setContent(mixed $content) : static`

Sets the content of the _Response_.

The `$content` may may be `null`, a string, a resource, an object, or anything
else. How the content will be sent is determined by the _Response_ sending
logic.

The method is fluent, allowing you to chain a call to another _Response_ method.

Note that unlike almost all the other _Response_ methods, `setContent()` is
**not** declared as `final`. This means you can override it in extended
_Response_ classes (though of course the signature must remain).

## Getting

`final public getContent() : mixed`

Returns the content of the _Response_.
