<?php
namespace Sapien\Response;

use Sapien\Exception;

class HeaderTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $header = new Header('foo');
        $this->assertSame('foo', $header->value);
        $header->add('bar');
        $this->assertSame('foo, bar', $header->value);

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Sapien\Response\Header::$nonesuch does not exist.');
        $header->nonesuch;
    }
}
