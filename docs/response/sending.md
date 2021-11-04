# Sending

`public function send() : void`

To send the _Response_, call its `send()` method. Doing so will:

- call each of the `$headerCallbacks` in order

- send the status line `$version` and `$code` using [`header()`](https://php.net/header)
  calls; the default version is 1.1 and the default code is 200

- send each of the `$headers` using [`header()`](https://php.net/header) calls

- send each of the `$cookies` using [`setcookie()`](https://php.net/setcookie)
  and [`setrawcookie()`](https://php.net/setrawcookie) as appropriate

- send the content using the _Response_ `sendContent()` method (see below for
  details).

Note that the `send()` method, unlike most _Response_ methods, is **not**
declared as final. This means you can override it in extended _Response_ classes
(though of course the signature must remain).

## Content Handling

`protected function sendContent() : void`

Recall that the `setContent()` method allows anything to be content: a string,
an object, a resource, etc. It is the `sendContent()` method that determines
how to actually send the _Response_ content.

If the content is ...

- **a resource or _SplFileObject_**, then `sendContent()` will `rewind()` it and
  send it with `fpassthru()`.

- **a non-string callable**, then `sendContent()` will invoke it. Further,
  `sendContent()` will echo the return value (if any) from that invocation. This
  means the callable may emit output itself, or it may return a string for
  `sendContent()` to echo, or do both.

- **an iterable**, then `sendContent()` will `foreach()` through it, and echo
  each value.

- **a string or a _Stringable_**, then `sendContent()` will merely echo it.

- **anything else**, then `sendContent()` will do nothing, and return.

The above conditions are in precedence order. That is, if the content is
*both* callable *and* iterable, the callable handling will take precedence
over the iterable handling.

Note that the `sendContent()` method, unlike most _Response_ methods, is **not**
declared as final. This means you can override it in extended _Response_ classes
(though of course the signature must remain).
