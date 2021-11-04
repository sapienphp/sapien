<?php
namespace Sapien;

class ValueObjectTest extends \PHPUnit\Framework\TestCase
{
    public function testGet()
    {
        $fake = new FakeValueObject();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Sapien\FakeValueObject::$foo does not exist.');
        $fake->foo;
    }

    public function testSet()
    {
        $fake = new FakeValueObject();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Sapien\FakeValueObject::$foo does not exist.');
        $fake->foo = 'bar';
    }

    public function testIsset()
    {
        $fake = new FakeValueObject();
        $this->assertFalse(isset($fake->foo));
    }

    public function testUnset()
    {
        $fake = new FakeValueObject();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Sapien\FakeValueObject::$foo does not exist.');
        unset($fake->foo);
    }
}
