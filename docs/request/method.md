# Method

The _Request_ `$method` property is a _Sapien\Request\Method_ instance derived
from the `$server` values.

The _Method_ `$name` property is a readonly string.

```php
// returns the derived method value
$requestMethod = $request->method->name;

// the method object is stringable:
assert($request->method->name === (string) $request->method);
```

The `$name` value is computed from the _Request_ `$server['REQUEST_METHOD']`
element, or the _Request_ `$server['HTTP_X_HTTP_METHOD_OVERRIDE']` element, as
appropriate.

In addition, the _Method_ object has an `is()` method for checking the method
name:

```php
$isPost = $request->method->is('post');
```

You can override the default method value with a custom one via the _Request_
constructor ...

```php
$request = new Request(
    method: 'delete',
);
```

... or you can provide a _Method_ object of your own construction:

```php
$request = new Request(
    method: new Request\Method('delete'),
);
