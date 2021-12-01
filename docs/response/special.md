# Specialized Responses

## FileResponse

The _FileResponse_ is customized for sending back downloads.

Use `setContent()` to specify a string path to the file to be sent, or an
_SplFileObject_ object:

```php
use Sapien\Response\FileResponse;

$fileResponse = new FileResponse();

// use a string path ...
$fileResponse->setContent('/path/to/file.txt');

// ... or an SplFileObject:
$fileResponse->setContent(new \SplFileObject('/path/to/file.txt'));
```

The _FileResponse_ will set itself up to send the file ...

- disposed as an 'attachment',
- with whatever `content-type` is already set (or `application/octet-stream` if none),
- using whatever `content-transfer-encoding` is already set (or `binary` if none),
- naming the download for the filename.

Alternatively, call the `setFile()` method for better control over some
aspects of the _FileResponse_:

```php
$fileResponse->setFile(
    file: '/path/to/file.b64',  // or an SplFileObject instance
    disposition: 'attachment',  // or 'inline'
    name: 'SomeOtherName.b64',  // an alternative name for the download
    type: 'text/plain'          // set this content-type
    encoding: 'base64'          // set this content-transfer-encoding
);
```

In any case, you may always modify the _FileResponse_ values after
`setContent()` or `setFile()`.

## JsonResponse

The _JsonResponse_ is customized for sending back JSON content.

Use `setContent()` to specify a value to be JSON-encoded at sending time:

```php
use Sapien\Response\JsonResponse;

$jsonResponse = new JsonResponse();

// set the content to be encoded
$jsonResponse->setContent(['foo' => 'bar']);
```

The _JsonResponse_ will set itself up with ...

- a `content-type` of `application/json`,
- the default `json_encode()` flags and depth.

Alternatively, call the `setJson()` method for better control over some aspects
of the _JsonResponse_:

```php
$jsonResponse->setJson(
    value: ['foo' => 'bar'],        // the value to be encoded
    type: 'application/foo+json',   // set this content-type
    flags: JSON_PRETTY_PRINT,       // alternative json_encode() flags
    depth: 128                      // alternative json_encode() depth
);
```

In any case, you may always modify the _JsonResponse_ values after
`setContent()` or `setJson()`.

Further, you may call `setJsonFlags()` and `setJsonDepth()` to modify the
flags and depth respectively.

Finally, when you actually `send()` it, the _JsonResponse_ will echo the
results passing the content through `json_encode()`.
