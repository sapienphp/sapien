<?php
namespace Sapien\Response;

class CookieTest extends \PHPUnit\Framework\TestCase
{
    public function test() : void
    {
        $cookie = new Cookie(
            'setcookie',
            'bar',
            [
                'expires' => 123,
                'path' => '/',
                'domain' => '.example.com',
                'secure' => true,
                'httponly' => true,
                'samesite' => "Strict",
                'nonesuch' => 'nonesuch',
            ]
        );

        $expect = [
            'func' => 'setcookie',
            'value' => 'bar',
            'options' => [
                'expires' => 123,
                'path' => '/',
                'domain' => '.example.com',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict',
            ],
        ];

        $this->assertSame($expect, $cookie->asArray());
    }
}
