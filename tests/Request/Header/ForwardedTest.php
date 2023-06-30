<?php
namespace Sapien\Request\Header;

use Sapien\Request;
use Sapien\Exception;

class ForwardedTest extends \PHPUnit\Framework\TestCase
{
    public function test() : void
    {
        $_SERVER = [
            'HTTP_FORWARDED' => 'For="[2001:db8:cafe::17]:4711", for=192.0.2.60;proto=http;by=203.0.113.43, for=192.0.2.43, 12.34.56.78',
        ];

        $request = new Request();
        $expect = new ForwardedCollection([
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
        ]);
        $this->assertEquals($expect, $request->forwarded);
    }

    public function testEmpty() : void
    {
        $_SERVER = [];
        $request = new Request();
        $this->assertTrue($request->forwarded->isEmpty());
    }
}
