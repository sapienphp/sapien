<?php
namespace Sapien;

class ValueCollectionTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $expect = [
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gir',
        ];

        $fakeValueCollection = new FakeValueCollection($expect);

        foreach ($fakeValueCollection as $key => $val) {
            $this->assertSame($expect[$key], $val);
            $this->assertTrue(isset($fakeValueCollection[$key]));
            $this->assertSame($expect[$key], $fakeValueCollection[$key]);
        }

        $this->assertFalse(isset($fakeValueCollection['nonesuch']));
        $this->assertFalse($fakeValueCollection->isEmpty());
    }

    public function testOffsetSet()
    {
        $fakeValueCollection = new FakeValueCollection();
        $this->expectException(Exception::CLASS);
        $fakeValueCollection['foo'] = 'bar';
    }

    public function testOffsetUnset()
    {
        $fakeValueCollection = new FakeValueCollection(['foo' => 'bar']);
        $this->expectException(Exception::CLASS);
        unset($fakeValueCollection['foo']);
    }
}
