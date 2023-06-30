<?php
declare(strict_types=1);

namespace Sapien\Request;

use Sapien\Request;
use Sapien\Exception;

class ContentTest extends \PHPUnit\Framework\TestCase
{
    public function testPhpInput() : void
    {
        // need a way to fake php://input
        // only way is `php ... < file_with_post_raw.txt`
        $request = new Request();
        $this->assertSame('', $request->content->body);
        $this->assertNull($request->content->charset);
        $this->assertNull($request->content->length);
        $this->assertNull($request->content->md5);
        $this->assertNull($request->content->type);
    }

    public function testNoSuchProperty() : void
    {
        $request = new Request();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Sapien\Request\Content::$nonesuch does not exist.');
        $request->content->nonesuch; // @phpstan-ignore-line intentional get of undefined property
    }

    public function testAll() : void
    {
        $content = 'Hello World!';
        $length = strlen($content);
        $md5 = md5($content);

        $_SERVER = [
            'CONTENT_LENGTH' => (string) $length,
            'CONTENT_TYPE' => 'text/plain; charset=utf-8',
            'HTTP_CONTENT_MD5' => $md5,
        ];

        $request = new Request(content: $content);

        $this->assertSame($content, $request->content->body);
        $this->assertSame('utf-8', $request->content->charset);
        $this->assertSame($length, $request->content->length);
        $this->assertSame($md5, $request->content->md5);
        $this->assertSame('text/plain', $request->content->type);
    }

    public function testEmptyType() : void
    {
        $_SERVER = [
            'CONTENT_TYPE' => '',
        ];

        $request = new Request();

        $this->assertNull($request->content->type);
        $this->assertNull($request->content->charset);
    }

    public function testTypeButNoCharset() : void
    {
        $_SERVER = [
            'CONTENT_TYPE' => 'text/plain; foo=bar',
        ];

        $request = new Request();

        $this->assertSame('text/plain', $request->content->type);
        $this->assertNull($request->content->charset);
    }

    public function testParsedBodyJson() : void
    {
        $_SERVER = ['CONTENT_TYPE' => 'application/json;charset=utf-8'];
        $expect = ['foo' => 'bar'];
        $request = new Request(content: (string) json_encode($expect));
        $this->assertSame($expect, $request->input);

        $request = new Request(content: 'null');
        $this->assertSame([], $request->input);
    }
}
