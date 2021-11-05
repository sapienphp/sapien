<?php
namespace Sapien\Request;

use Sapien\Request;
use Sapien\Exception;
use Sapien\Request\Header\Forwarded;
use Sapien\Request\Header\XForwarded;

class ForwardedTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $_SERVER = [
            'HTTP_FORWARDED' => 'For="[2001:db8:cafe::17]:4711", for=192.0.2.60;proto=http;by=203.0.113.43, for=192.0.2.43, 12.34.56.78',
        ];

        $request = new Request();
        $expect = [
            new Forwarded(
                for: '[2001:db8:cafe::17]:4711',
            ),
            new Forwarded(
                for: "192.0.2.60",
                proto: "http",
                by: "203.0.113.43",
            ),
            new Forwarded(
                for: "192.0.2.43",
            ),
            new Forwarded(
            ),
        ];
        $this->assertEquals($expect, $request->forwarded);
    }

    public function testEmpty()
    {
        $_SERVER = [];
        $request = new Request();
        $this->assertEmpty($request->forwarded);
    }
}
