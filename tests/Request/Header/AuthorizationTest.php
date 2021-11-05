<?php
namespace Sapien\Request\Header;

use Sapien\Request;
use Sapien\Exception;

class AuthorizationTest extends \PHPUnit\Framework\TestCase
{
    public function testBasic()
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'BaSiC    ' . base64_encode('boshag:bopass');
        $request = new Request();
        $this->assertInstanceOf(Authorization\Scheme\Basic::CLASS, $request->authorization);
        $this->assertSame('boshag', $request->authorization->username);
        $this->assertSame('bopass', $request->authorization->password);
        $this->assertTrue($request->authorization->is('bASIc'));
    }

    public function testBearer()
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer foobarbaz';
        $request = new Request();
        $this->assertInstanceOf(Authorization\Scheme\Bearer::CLASS, $request->authorization);
        $this->assertSame('foobarbaz', $request->authorization->token);
        $this->assertTrue($request->authorization->is('bearer'));
    }

    public function testDigest()
    {
        $parts = [
            'username="boshag"',
            'realm="test@example.org"',
            'nonce="dcd98b7102dd2f0e8b11d0f600bfb0c093"',
            'uri="/foo/bar"',
            'qop=auth',
            'nc=00000001',
            'cnonce="0a4f113b"',
            'response="6629fae49393a05397450978507c4ef1"',
            'opaque="5ccc069c403ebaf9f0171e9517f40e41"',
            'userhash=false',
        ];
        $_SERVER['HTTP_AUTHORIZATION'] = 'Digest ' . implode(', ', $parts);
        $request = new Request();
        $this->assertInstanceOf(Authorization\Scheme\Digest::CLASS, $request->authorization);
        $this->assertSame('boshag', $request->authorization->username);
        $this->assertSame('test@example.org', $request->authorization->realm);
        $this->assertSame('dcd98b7102dd2f0e8b11d0f600bfb0c093', $request->authorization->nonce);
        $this->assertSame('/foo/bar', $request->authorization->uri);
        $this->assertSame('auth', $request->authorization->qop);
        $this->assertSame(1, $request->authorization->nc);
        $this->assertSame('0a4f113b', $request->authorization->cnonce);
        $this->assertSame('6629fae49393a05397450978507c4ef1', $request->authorization->response);
        $this->assertSame('5ccc069c403ebaf9f0171e9517f40e41', $request->authorization->opaque);
        $this->assertTrue($request->authorization->is('digest'));
    }

    public function testNone()
    {
        $request = new Request();
        $this->assertInstanceOf(Authorization\None::CLASS, $request->authorization);
        $this->assertTrue($request->authorization->is(null));
    }

    public function testGeneric()
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Foo barbazdib';
        $request = new Request();
        $this->assertInstanceOf(Authorization\Generic::CLASS, $request->authorization);
        $this->assertSame('Foo', $request->authorization->scheme);
        $this->assertSame('barbazdib', $request->authorization->credentials);
        $this->assertTrue($request->authorization->is('foo'));
   }
}
