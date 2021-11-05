<?php
namespace Sapien\Request\Header;

use Sapien\Exception;
use Sapien\Request;

class AcceptTest extends \PHPUnit\Framework\TestCase
{
    public function testTypes()
    {
        $_SERVER['HTTP_ACCEPT'] = 'application/xml;q=0.8, application/json;foo=bar, text/*;q=0.2, */*;q=0.1';
        $request = new Request();
        $expect = [
            new Accept\Type(
                value: 'application/json',
                quality: '1.0',
                params: [
                    'foo' => 'bar',
                ],
            ),
            new Accept\Type(
                value: 'application/xml',
                quality: '0.8',
                params: [],
            ),
            new Accept\Type(
                value: 'text/*',
                quality: '0.2',
                params: [],
            ),
            new Accept\Type(
                value: '*/*',
                quality: '0.1',
                params: [],
            ),
        ];
          $this->assertEquals($expect, $request->accept->types);
    }

    public function testCharsets()
    {
        $_SERVER['HTTP_ACCEPT_CHARSET'] = 'iso-8859-5;q=0.8, unicode-1-1';
        $request = new Request();
        $expect = [
            new Accept\Charset(
                value: 'unicode-1-1',
                quality: '1.0',
                params: [],
            ),
            new Accept\Charset(
                value: 'iso-8859-5',
                quality: '0.8',
                params: [],
            ),
            ];
        $this->assertEquals($expect, $request->accept->charsets);
    }

    public function testEncodings()
    {
        $_SERVER['HTTP_ACCEPT_ENCODING'] = 'compress;q=0.5, gzip;q=1.0';
        $request = new Request();
        $expect = [
            new Accept\Encoding(
                value: 'gzip',
                quality: '1.0',
                params: [],
            ),
            new Accept\Encoding(
                value: 'compress',
                quality: '0.5',
                params: [],
            ),
        ];
        $this->assertEquals($expect, $request->accept->encodings);
    }

    public function testLanguages()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en-US, en-GB, en, *';
        $request = new Request();
        $expect = [
            new Accept\Language(
                value: 'en-US',
                quality: '1.0',
                params: [],
                type: 'en',
                subtype: 'US',
            ),
            new Accept\Language(
                value: 'en-GB',
                quality: '1.0',
                params: [],
                type: 'en',
                subtype: 'GB',
            ),
            new Accept\Language(
                value: 'en',
                quality: '1.0',
                params: [],
                type: 'en',
                subtype: NULL,
            ),
            new Accept\Language(
                value: '*',
                quality: '1.0',
                params: [],
                type: '*',
                subtype: NULL,
            ),
        ];
        $this->assertEquals($expect, $request->accept->languages);
    }

    public function testEmpty()
    {
        $_SERVER['HTTP_ACCEPT'] = '';
        $request = new Request();
        $this->assertEmpty($request->accept->types);
    }
}
