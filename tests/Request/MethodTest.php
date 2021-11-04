<?php
namespace Sapien\Request;

use Sapien\Request;

class MethodTest extends \PHPUnit\Framework\TestCase
{
    public function testTypical()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $request = new Request();
        $this->assertSame('GET', $request->method->name);
        $this->assertSame('GET', (string) $request->method);
        $this->assertTrue($request->method->is('get'));
        $this->assertFalse($request->method->is('post'));
    }

    public function testOverride()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] = 'PATCH';
        $request = new Request();
        $this->assertSame('PATCH', $request->method->name);
    }

    public function testExplicit()
    {
        $request = new Request(method: 'DELETE');
        $this->assertSame('DELETE', $request->method->name);
    }

    public function testEmpty()
    {
        $_SERVER = [];
        $request = new Request();
        $this->assertNull($request->method->name);

        $_SERVER = null;
        $request = new Request();
        $this->assertNull($request->method->name);
    }
}
