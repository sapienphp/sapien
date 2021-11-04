# Extending the _Request_

The Sapien _Request_ class can be extended to provide other userland
functionality.

## Constructor

The _Request_ class has a constructor. Child classes overriding `__construct()`
should be sure to call `parent::__construct()`, or else the parent readonly
properties will remain uninitialized. Likewise, child classes specifying a
constructor will need to duplicate the parent parameters.

## Properties and Methods

The parent _Request_ properties are readonly and cannot be modified or
overridden. However, child classes may add new properties as desired. Even so,
Sapien reserves the right to add new properties named for HTTP headers, along
with `new*()` methods to populate those properties on demand.

For example, try not to not add properties and methods named for HTTP headers ...

```php
class MyRequest extends \Sapien\Request
{
    // ...

    protected MyRequest\Authorization $authorization;

    protected function newAuthorization() : MyRequest\Authorization
    {
        // ...
    }
}
```

... but you may add methods and properties for application-specific needs.
For example, immutable application attributes:

```php
class MyRequest extends \Sapien\Request
{
    // ...

    protected $attributes = [];

    public function withAttributes(array $attribues) : static
    {
        $clone = clone $this;
        $clone->attributes = $attributes;
        return $clone;
    }

    public function withAttribute(string $key, mixed $value) : static
    {
        $clone = clone $this;
        $clone->attributes[$key] = $value;
        return $clone;
    }
}
```

## Magic Methods

Protected properties in child classes will automatically be available via magic
`__get()`, though private properties will not be.

The methods `__set()`, `__isset()`, and `__unset()` are declared `final` and so
cannot be overridden. This is to help prevent subversion of the readonly nature
of _Request_.
