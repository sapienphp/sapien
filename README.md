# Sapien

This package provides server API (SAPI) request and response objects for PHP
8.1:

- _Sapien\Request_, composed of readonly copies of PHP superglobals and some other
   commonly-used values

- _Sapien\Response_, a wrapper around (and buffer for) response-related PHP
   functions

These are *not* HTTP message objects proper. Instead, they are wrappers and
buffers for existing global PHP variables and functions.

Install this package via Composer:

```
composer require sapien/sapien
```

Read the docs at <http://sapienphp.com>.
