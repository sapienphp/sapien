<?php
declare(strict_types=1);

namespace Sapien;

use Sapien\Response\Cookie;
use Sapien\Response\Header;
use SplFileObject;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    use Response\Assertions;

    public const OUTPUT = 'Hello World!';

    public function testVersion() : void
    {
        $response = new Response();
        $response->setVersion('2');
        $this->assertSame('2', $response->getVersion());
    }

    public function testCode() : void
    {
        $response = new Response();
        $response->setCode(123);
        $this->assertSame(123, $response->getCode());
    }

    protected function assertHeader(Response $response, string $label, string $expect) : void
    {
        /** @var Header */
        $actual = $response->getHeader($label);
        $this->assertSame($expect, $actual->value);
    }

    public function testHeaders() : void
    {
        $response = new Response();

        $response->setHeader('FOO', 'bar');
        $this->assertHeader($response, 'foo', 'bar');

        $response->addHeader('foo', 'baz');
        $this->assertHeader($response, 'foo', 'bar, baz');

        $response->addHeader('dib', 'zim');
        $this->assertHeader($response, 'dib', 'zim');

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

        $this->assertHeader($response, 'foo', 'bar');
        $this->assertHeader($response, 'baz', 'dib');
        $this->assertHeader($response, 'zim', 'gir');
    }

    /**
     * @dataProvider provideBadHeaderLabel
     */
    public function testBadHeaderLabel(string $method, string $label, string $value) : void
    {
        $response = new Response();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Header label cannot be blank');
        $response->$method($label, $value);
    }

    /**
     * @return array<int, array{string, string, string}>
     */
    public static function provideBadHeaderLabel() : array
    {
        return [
            ['setHeader', '', 'value'],
            ['addHeader', '', 'value'],
        ];
    }

    /**
     * @dataProvider provideBadHeaderValue
     */
    public function testBadHeaderValue(string $method, string $label, string $value) : void
    {
        $response = new Response();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Header value cannot be blank');
        $response->$method($label, $value);
    }

    /**
     * @return array<int, array{string, string, string}>
     */
    public static function provideBadHeaderValue() : array
    {
        return [
            ['setHeader', 'label', ''],
            ['addHeader', 'label', ''],
        ];
    }

    public function testCookies() : void
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

        /** @var Cookie */
        $actual = $response->getCookie('foo');
        $this->assertSame($expect, $actual->asArray());

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

        /** @var Cookie */
        $actual = $response->getCookie('baz');
        $this->assertSame($expect, $actual->asArray());

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

    public function testHeaderCallbacks() : void
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
        $this->assertCount(0, $response->getHeaderCallbacks());
    }

    public function testContent() : void
    {
        $response = new Response();
        $response->setContent('foo');
        $this->assertSame('foo', $response->getContent());
    }


    public function testSend() : void
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

    public function testSendContentIsEmpty() : void
    {
        $response = new Response();
        $this->assertSent($response, 200, [], '');
    }

    public function testSendContentIsIterable() : void
    {
        $response = new Response();
        $response->setContent(['Hello ', 'World!']);
        $this->assertSent($response, 200, [], static::OUTPUT);
    }

    public function testSendContentIsResource() : void
    {
        $response = new Response();
        $response->setContent(fopen(__DIR__ . '/Response/fake-content.txt', 'rb'));
        $this->assertSent($response, 200, [], static::OUTPUT);
    }

    public function testSendContentIsSplFileObject() : void
    {
        $response = new Response();

        $response->setContent(new SplFileObject(
            __DIR__ . '/Response/fake-content.txt',
            'rb'
        ));
        $this->assertSent($response, 200, [], static::OUTPUT);
    }

    public function testSendContentIsCallableReturning() : void
    {
        $response = new Response();
        $response->setContent(function () : string {
            return static::OUTPUT;
        });
        $this->assertSent($response, 200, [], static::OUTPUT);
    }

    public function testSendContentIsCallableEchoing() : void
    {
        $response = new Response();
        $response->setContent(function () : void {
            echo static::OUTPUT;
        });
        $this->assertSent($response, 200, [], static::OUTPUT);
    }
}
