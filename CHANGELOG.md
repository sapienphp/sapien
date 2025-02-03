# Change Log

## 1.1.2

- improve handling of deeply-grouped upload arrays

- add FilesArray and FilesArrayNested phpstan types, and modifies FileArray type

- remove PHP 8.4 notices by explicitly allowing nulls for implicitly nullable params

## 1.1.1

This is a hygiene release, with improved static analysis typehinting
and upgraded testing.

## 1.1.0

- add JsonResponse::setFlags() and setDepth()

- add Response::setCookies()

- Response::setCookie() now takes a Cookie instance

- JsonResponse now honors any pre-existing content-type

## 1.0.0

Initial release.

