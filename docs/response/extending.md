# Extending the _Response_

The Sapien _Response_ class can be extended to provide other userland
functionality.

## Properties

The properties on _Response_ are private, which means you may not access
them, except through the existing _Response_ methods. You may add child
properties as desired, though they would best be `protected` or `private`.

## Constructor

_Response_ is constructorless. You may add any constructor you
like, and do not have to call a parent constructor.

## Methods

Most of the methods on _Response_ are public **and final**, which means you
cannot extend or override them in child classes. This keeps their behavior
consistent.

However, these _Response_ methods are **not** final, and thus are open to
extension:

- `public function setContent(mixed $content) : void`
- `public function send() : void`
- `public sendContent() : void`

You may override them at will (though of course you cannot change the
signatures). In general:

- Override `setContent()` to set up the _Response_ properties in relation to
  the content. Be sure to call `parent::setContent()` to actually retain the
  content value.

- Override `send()` to perform pre- and post-sending behaviors. Be sure to call
  `parent::send()` to actually send the _Response_.

- Override `sendContent()` for custom or specialized emitting of the content
  value.
