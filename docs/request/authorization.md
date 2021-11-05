# Authorization

The _Request_ `$authorization` property is
a _Sapien\Request\Authorization\Scheme_ object.

The _Scheme_ class itself is a marker, and may be one of several
different implementations. The implementation is based on the scheme indicated
by the _Request_ `$headers['authorization']` scheme.

> **Warning:**
>
> The _Authorization\Scheme_ objects **do not** indicate a user has been
> authenticated or authorized. They only carry the structured header values
> provided by the client. Use them to perform your own authentication and
> authorization logic.

## Basic

The _Basic_ scheme presents these readonly properties computed from
the _Request_ `$headers['authorization']` credentials:

- `string $username`: The base64-decoded username.
- `string $password`: The base64-decoded password.

## Bearer

The _Bearer_ scheme presents this readonly property computed from the _Request_
`$headers['authorization']` credentials:

- `string $token`: The bearer token.

## Digest

The _Digest_ scheme presents these readonly properties computed from
the _Request_ `$headers['authorization']` credentials:

- `?string $cnonce`: The client nonce.
- `?string $nc`: The nonce count.
- `?string $nonce`: The server nonce.
- `?string $opaque`: The server opaque string.
- `?string $qop`: The quality of protected.
- `?string $realm`: The authentication realm.
- `?string $response`: The client response.
- `?string $uri`: The effective request URI.
- `?string $userhash`: Whether or not the username has been hashed.
- `?string $username`: The username in the realm.

## Generic

The _Generic_ scheme is used when the authorization scheme does not
have a corresponding class. It presents these readonly properties:

- `string $scheme`: The authorization scheme.
- `string $credentials`: The authorization credentials.

## None

The _None_ scheme is empty, and indicates there was no authorization header.
