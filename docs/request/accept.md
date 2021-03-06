# Accept

The _Request_ `$accept` property is a _Sapien\Request\Header\Accept_ object.

The _Accept_ object has these readonly _ValueCollection_ properties:

- `TypeCollection $types`: A collection of _Accept\Type_ objects computed from
  `$header['accept']`.

- `CharsetCollection $charsets`: A collection of _Accept\Charset_ objects computed from
  `$header['accept-charset']`.

- `EncodingCollection $encodings`: A collection of _Accept\Encoding_ objects computed from
  `$header['accept-encoding']`.

- `LanguageCollection $languages`: A collection of _Accept\Language_ objects computed from
  `$header['accept-language']`.

Each collection is sorted from highest `q` parameter value to lowest.

Each _Accept\\*_ object has these readonly properties:

- `string $value`: The main value of the accept header.

- `string $quality`: The 'q=' parameter value.

- `array $params`: A key-value array of all other parameters.

Each _Accept\Language_ object has these additional readonly properties:

- `string $type`: The language type.

- `?string $subtype`: The language subtype, if any.
