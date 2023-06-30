<?php
declare(strict_types=1);

namespace Sapien\Response;

use Sapien\Exception;

class HeaderTest extends \PHPUnit\Framework\TestCase
{
    public function test() : void
    {
        $header = new Header('foo');
        $this->assertSame('foo', $header->value);
        $header->add('bar');
        $this->assertSame('foo, bar', $header->value);

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Sapien\Response\Header::$nonesuch does not exist.');
        $header->nonesuch; // @phpstan-ignore-line intentional get of undefined property
    }
}
