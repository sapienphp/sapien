<?php
declare(strict_types=1);

namespace Sapien;

use Error;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function setUp() : void
    {
        $_GET = [];
        $_POST = [];
        $_SERVER = [];
        $_FILES = [];
        $_COOKIE = [];
    }

    public function test() : void
    {
        $request = new Request();
        $this->assertInstanceOf(Request::CLASS, $request);
        $this->assertTrue(empty($request->headers));
        $this->assertTrue($request->uploads->isEmpty());
        $this->assertInstanceOf(Request\Method::CLASS, $request->method);
        $this->assertInstanceOf(Request\Url::CLASS, $request->url);
        $this->assertTrue(isset($request->accept));
    }

    public function testMissingProperty() : void
    {
        $request = new Request();
        $this->assertFalse(isset($request->nonesuch));

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Sapien\Request::$nonesuch does not exist.');
        $request->nonesuch; // @phpstan-ignore-line intentional get of undefined property
    }

    /**
     * @dataProvider provideMostGlobals
     */
    public function testMostGlobals(string $GLOBAL, string $prop) : void
    {
        $GLOBALS[$GLOBAL] = ['foo' => 'bar'];
        $request = new Request();
        $this->assertSame($GLOBALS[$GLOBAL], $request->$prop);

        $GLOBALS[$GLOBAL] = ['baz' => 'dib'];
        $this->assertNotSame($GLOBALS[$GLOBAL], $request->$prop);

        $expect = ['zim' => 'gir'];
        $request = new Request(globals: [$GLOBAL => $expect]);
        $this->assertSame($expect, $request->$prop);

        $this->expectException(Error::CLASS);
        $this->expectExceptionMessage('Cannot modify readonly property Sapien\Request::$' . $prop);
        $request->$prop['zim'] = 'doom';
    }

    /**
     * @return array<int, array{string, string}>
     */
    public static function provideMostGlobals() : array
    {
        return [
            ['_COOKIE', 'cookies'],
            ['_POST', 'input'],
            ['_GET', 'query'],
            ['_SERVER', 'server'],
        ];
    }

    public function testFiles() : void
    {
        $_FILES = [
          'foo1' => [
            'error' => 0,
            'name' => '',
            'full_path' => '',
            'size' => 0,
            'tmp_name' => '',
            'type' => '',
          ],
        ];

        $request = new Request();

        $this->assertSame($GLOBALS['_FILES'], $request->files);

        $_FILES = ['baz' => 'dib'];
        $this->assertNotSame($GLOBALS['_FILES'], $request->files);

        $expect = [
          'foo2' => [
            'error' => 0,
            'name' => '',
            'full_path' => '',
            'size' => 0,
            'tmp_name' => '',
            'type' => '',
          ],
        ];

        $request = new Request(globals: ['_FILES' => $expect]);
        $this->assertSame($expect, $request->files);

        $this->expectException(Error::CLASS);
        $this->expectExceptionMessage('Cannot modify readonly property Sapien\Request::$files');
        $request->files['zim'] = 'doom'; // @phpstan-ignore-line intentional set of readonly property
    }

    public function testImmutable() : void
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Immutable values must be null, scalar, or array.');
        $request = new Request(globals: ['_SERVER' => ['foo' => new \stdClass()]]);
    }

    public function testHeaders() : void
    {
        $request = new Request(
            globals: [
                '_SERVER' => [
                    'HTTP_HOST' => 'example.com',
                    'HTTP_FOO_BAR_BAZ' => 'dib,zim,gir',
                    'NON_HTTP_HEADER' => 'should not show',
                    'CONTENT_LENGTH' => '123',
                    'CONTENT_TYPE' => 'text/plain',
                ],
            ],
        );

        $expect = [
            'host' => 'example.com',
            'foo-bar-baz' => 'dib,zim,gir',
            'content-length' => '123',
            'content-type' => 'text/plain',
        ];

        $this->assertSame($expect, $request->headers);
    }
}
