<?php
namespace Sapien\Response;

use Sapien\Response;

trait Assertions
{
    protected function assertSent(
        Response $response,
        int $code,
        array $headers,
        string $content
    ) : void
    {
        ob_start();
        $response->send();
        $output = ob_get_clean();
        $this->assertSame($code, http_response_code());
        $this->assertSame($headers, xdebug_get_headers());
        $this->assertSame($content, $output);
    }
}
