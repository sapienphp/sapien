<?php
namespace Sapien\Request\Header;

use Sapien\Request;
use Sapien\Exception;

class XForwardedTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $_SERVER = [
            'HTTP_X_FORWARDED_FOR' => '1.2.3.4, 5.6.7.8, 9.10.11.12',
            'HTTP_X_FORWARDED_HOST' => 'example.net',
            'HTTP_X_FORWARDED_PROTO' => 'https',
            'HTTP_X_FORWARDED_PORT' => '123',
            'HTTP_X_FORWARDED_PREFIX' => '/prefix'
        ];

        $request = new Request();

        $expect = [
            "1.2.3.4",
            "5.6.7.8",
            "9.10.11.12",
        ];
        $this->assertSame($expect, $request->xForwarded->for);

        $expect = "example.net";
        $this->assertSame($expect, $request->xForwarded->host);

        $expect = "https";
        $this->assertSame($expect, $request->xForwarded->proto);

        $expect = 123;
        $this->assertSame($expect, $request->xForwarded->port);

        $expect = '/prefix';
        $this->assertSame($expect, $request->xForwarded->prefix);
    }

    public function testEmpty()
    {
        $_SERVER = [];
        $request = new Request();
        $this->assertTrue(empty($request->xForwarded->for));
        $this->assertNull($request->xForwarded->host);
        $this->assertNull($request->xForwarded->proto);
        $this->assertNull($request->xForwarded->port);
        $this->assertNull($request->xForwarded->prefix);
    }
}
