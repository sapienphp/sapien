<?php
declare(strict_types=1);

namespace Sapien;

class ValueObjectTest extends \PHPUnit\Framework\TestCase
{
    public function testGet() : void
    {
        $fake = new FakeValueObject();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Sapien\FakeValueObject::$foo does not exist.');
        $fake->foo; // @phpstan-ignore-line intentional get of undefined property
    }

    public function testSet() : void
    {
        $fake = new FakeValueObject();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Sapien\FakeValueObject::$foo does not exist.');
        $fake->foo = 'bar'; // @phpstan-ignore-line intentional set of undefined property
    }

    public function testIsset() : void
    {
        $fake = new FakeValueObject();
        $this->assertFalse(isset($fake->foo));
    }

    public function testUnset() : void
    {
        $fake = new FakeValueObject();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Sapien\FakeValueObject::$foo does not exist.');
        unset($fake->foo);
    }
}
