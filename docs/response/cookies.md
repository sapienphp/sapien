# Cookies

The _Response_ retains each cookie as a _Sapien\Response\Cookie_ value object.

## Setting

### Setting One Encoded Cookie

```
final public setCookie(
    string $name,
    string $value = '',
    int $expires = null,
    string $path = null,
    string $domain = null,
    bool $secure = null,
    bool $httponly = null,
    string $samesite = null
) : static
```

A buffered equivalent of [`setcookie()`](http://php.net/setcookie), with the
various options expanded out to method parameters.

The method is fluent, allowing you to chain a call to another _Response_ method.

### Setting One Raw Cookie

```
final public setRawCookie(
    string $name,
    string $value = '',
    int $expires = null,
    string $path = null,
    string $domain = null,
    bool $secure = null,
    bool $httponly = null,
    string $samesite = null
) : static
```

A buffered equivalent of [`setrawcookie()`](http://php.net/setrawcookie), with
the various options expanded out to method parameters.

The method is fluent, allowing you to chain a call to another _Response_ method.

### Setting One Cookie Instance

```
final public setCookie(
    string $name,
    Cookie $value
) : static
```

If you have a _Cookie_ instance in hand, you may set it into the _Reponse_ using
`setCookie()`.

The method is fluent, allowing you to chain a call to another _Response_ method.

### Setting All Cookies

`final public setCookies(array $cookies) : static`

Resets the _Response_ cookies to the key-value pairs of `$cookies`. The value
may be a string, in which case the value will be encoded, or it may be a
_Cookie_ instance, in which case it will be retained as-is.

## Getting

### Getting One Cookie

`final public getCookie(string $name) : ?Cookie`

Returns the `$name` _Cookie_ from the _Response_.

### Getting All Cookies

`final public getCookies() : array`

Returns the array of _Cookie_ objects in the _Response_.

## Checking

`final public hasCookie(string $name) : bool`

Returns `true` if the `$name` _Cookie_ exists in the _Response_, `false` if not.

## Removing

### Removing One Cookie

`final public unsetCookie(string $name) : static`

Removes the `$name` _Cookie_ from the _Response_.

The method is fluent, allowing you to chain a call to another _Response_ method.

### Removing All Cookies

`final public unsetCookies() : static`

Removes all _Cookie_ objects from the _Response_.

The method is fluent, allowing you to chain a call to another _Response_ method.
