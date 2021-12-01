<?php
namespace Sapien;

use SplFileObject;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    use Response\Assertions;

    public const OUTPUT = 'Hello World!';

    public function testVersion()
    {
        $response = new Response();
        $response->setVersion('2');
        $this->assertSame('2', $response->getVersion());
    }

    public function testCode()
    {
        $response = new Response();
        $response->setCode(123);
        $this->assertSame(123, $response->getCode());
    }

    public function testHeaders()
    {
        $response = new Response();

        $response->setHeader('FOO', 'bar');
        $this->assertSame('bar', $response->getHeader('foo')->value);

        $response->addHeader('foo', 'baz');
        $this->assertSame('bar, baz', $response->getHeader('foo')->value);

        $response->addHeader('dib', 'zim');
        $this->assertSame('zim', $response->getHeader('dib')->value);

        $headers = $response->getHeaders();
        $this->assertCount(2, $response->getHeaders());

        $this->assertTrue($response->hasHeader('foo'));
        $response->unsetHeader('foo');
        $this->assertFalse($response->hasHeader('foo'));

        $response->unsetHeaders();
        $this->assertTrue(empty($response->getHeaders()));

        $response->setHeaders([
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gir',
        ]);

        $headers = $response->getHeaders();
        $this->assertCount(3, $response->getHeaders());

        $this->assertSame('bar', $response->getHeader('foo')->value);
        $this->assertSame('dib', $response->getHeader('baz')->value);
        $this->assertSame('gir', $response->getHeader('zim')->value);
    }

    /**
     * @dataProvider provideBadHeaderLabel
     */
    public function testBadHeaderLabel($method, $label, $value)
    {
        $response = new Response();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Header label cannot be blank');
        $response->$method($label, $value);
    }

    public function provideBadHeaderLabel()
    {
        return [
            ['setHeader', '', 'value'],
            ['addHeader', '', 'value'],        ];
    }

    /**
     * @dataProvider provideBadHeaderValue
     */
    public function testBadHeaderValue($method, $label, $value)
    {
        $response = new Response();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Header value cannot be blank');
        $response->$method($label, $value);
    }

    public function provideBadHeaderValue()
    {
        return [
            ['setHeader', 'label', ''],
            ['addHeader', 'label', ''],
        ];
    }

    public function testCookies()
    {
        $response = new Response();

        $response->setCookie(
            name: 'foo',
            value: 'bar',
        );

        $expect = [
            'func' => 'setcookie',
            'value' => 'bar',
            'options' => [
            ],
        ];

        $this->assertSame($expect, $response->getCookie('foo')->asArray());

        $response->setRawCookie(
            name: 'baz',
            value: 'dib',
        );

        $expect = [
            'func' => 'setrawcookie',
            'value' => 'dib',
            'options' => [
            ],
        ];

        $this->assertSame($expect, $response->getCookie('baz')->asArray());

        $expect = ['foo', 'baz'];
        $cookies = $response->getCookies();
        $this->assertSame($expect, array_keys($cookies));


        $this->assertTrue($response->hasCookie('foo'));
        $response->unsetCookie('foo');
        $this->assertFalse($response->hasCookie('foo'));

        $response->unsetCookies();
        $this->assertTrue(empty($response->getCookies()));

        $response->setCookies($cookies);
        $this->assertEquals($cookies, $response->getCookies());
    }

    public function testHeaderCallbacks()
    {
        $response = new Response();

        $this->assertFalse($response->hasHeaderCallbacks());

        $response->addHeaderCallback('ltrim');
        $response->addHeaderCallback('rtrim');
        $expect = ['ltrim', 'rtrim'];
        $this->assertSame($expect, $response->getHeaderCallbacks());

        $response->setHeaderCallbacks(['rtrim', 'ltrim']);
        $expect = ['rtrim', 'ltrim'];
        $this->assertSame($expect, $response->getHeaderCallbacks());

        $this->assertTrue($response->hasHeaderCallbacks());

        $response->unsetHeaderCallbacks();
        $this->assertTrue(empty($response->getHeaderCallbacks()));
    }

    public function testContent()
    {
        $response = new Response();
        $response->setContent('foo');
        $this->assertSame('foo', $response->getContent());
    }


    public function testSend()
    {
        $response = new Response();
        $response->setCode(206);
        $response->addHeader('foo', 'bar');
        $response->setCookie('baz', 'dib');
        $response->setContent('Hello World!');
        $response->addHeaderCallback(function ($response) {
            $response->addHeader('zim', 'gir');
        });

        $this->assertSent(
            $response,
            206,
            [
                'foo: bar',
                'zim: gir',
                'Set-Cookie: baz=dib',
            ],
            static::OUTPUT
        );
    }

    public function testSendContentIsEmpty()
    {
        $response = new Response();
        $this->assertSent($response, 200, [], '');
    }

    public function testSendContentIsIterable()
    {
        $response = new Response();
        $response->setContent(['Hello ', 'World!']);
        $this->assertSent($response, 200, [], static::OUTPUT);
    }

    public function testSendContentIsResource()
    {
        $response = new Response();
        $response->setContent(fopen(__DIR__ . '/Response/fake-content.txt', 'rb'));
        $this->assertSent($response, 200, [], static::OUTPUT);
    }

    public function testSendContentIsSplFileObject()
    {
        $response = new Response();

        $response->setContent(new SplFileObject(
            __DIR__ . '/Response/fake-content.txt',
            'rb'
        ));
        $this->assertSent($response, 200, [], static::OUTPUT);
    }

    public function testSendContentIsCallableReturning()
    {
        $response = new Response();
        $response->setContent(function () : string {
            return static::OUTPUT;
        });
        $this->assertSent($response, 200, [], static::OUTPUT);
    }

    public function testSendContentIsCallableEchoing()
    {
        $response = new Response();
        $response->setContent(function () : void {
            echo static::OUTPUT;
        });
        $this->assertSent($response, 200, [], static::OUTPUT);
    }
}
