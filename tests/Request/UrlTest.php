<?php
namespace Sapien\Request;

use Sapien\Request;

class UrlTest extends \PHPUnit\Framework\TestCase
{
    public function testExplicit() : void
    {
        $request = new Request(url: [
            'scheme' => 'http',
            'host' => 'example.com',
            'port' => 8080,
            'path' => '/foo/bar',
            'query' => 'baz=dib',
        ]);

        $expect = [
            'scheme' => 'http',
            'host' => 'example.com',
            'port' => 8080,
            'user' => null,
            'pass' => null,
            'path' => '/foo/bar',
            'query' => 'baz=dib',
            'fragment' => null,
        ];

        $this->assertSame($expect, $request->url->asArray());
    }

    public function testTypical() : void
    {
        $_SERVER = [
            'HTTP_HOST' => 'example.com',
            'SERVER_PORT' => '8080',
            'REQUEST_URI' => '/foo/bar?baz=dib',
        ];

        $request = new Request();

        $expect = [
            'scheme' => 'http',
            'host' => 'example.com',
            'port' => 8080,
            'user' => null,
            'pass' => null,
            'path' => '/foo/bar',
            'query' => 'baz=dib',
            'fragment' => null,
        ];

        $this->assertSame($expect, $request->url->asArray());
    }

    public function testEmpty() : void
    {
        $expect = [
            'scheme' => null,
            'host' => null,
            'port' => null,
            'user' => null,
            'pass' => null,
            'path' => null,
            'query' => null,
            'fragment' => null,
        ];

        $_SERVER = [];
        $request = new Request();
        $this->assertSame($expect, $request->url->asArray());

        $_SERVER = null;
        $request = new Request();
        $this->assertSame($expect, $request->url->asArray());
    }

    public function testHttps() : void
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'example.com';

        $request = new Request();
        $this->assertSame('https', $request->url->scheme);
        $this->assertSame('example.com', $request->url->host);
    }

    public function testNoHost() : void
    {
        $_SERVER['REQUEST_URI'] = '/';
        $request = new Request();
        $expect = [
            'scheme' => 'http',
            'host' => null,
            'port' => null,
            'user' => null,
            'pass' => null,
            'path' => '/',
            'query' => null,
            'fragment' => null,
        ];
        $this->assertSame($expect, $request->url->asArray());
    }

    public function testHostPort() : void
    {
        $_SERVER = [
            'HTTP_HOST' => 'example.com:8080',
        ];

        $request = new Request();
        $this->assertSame('example.com', $request->url->host);
        $this->assertSame(8080, $request->url->port);
    }

    /**
     * @dataProvider provideString
     */
    public function testString(string $expect) : void
    {
        $request = new Request(url: parse_url($expect));
        $this->assertSame($expect, (string) $request->url);
    }

    public static function provideString()
    {
        return [
            ['http://user:pass@example.com:8000/foo?bar=baz#dib'],
            ['http://user:pass@example.com:8000/foo?bar=baz'],
            ['http://user:pass@example.com:8000/foo#dib'],
            ['http://user:pass@example.com:8000/?bar=baz'],
            ['http://user:pass@example.com:8000/foo'],
            ['http://user:pass@example.com:8000/'],
            ['http://user:pass@example.com:8000'],
            ['http://user:pass@example.com'],
            ['http://user@example.com'],
            ['http://example.com'],
        ];
    }
}
