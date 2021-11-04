<?php
namespace Sapien\Response;

class JsonResponseTest extends \PHPUnit\Framework\TestCase
{
    use Assertions;

    public function test()
    {
        $response = new JsonResponse();
        $response->setContent(['Hello ', 'World!']);
        $this->assertSent(
            $response,
            200,
            [
                'content-type: application/json'
            ],
            json_encode(['Hello ', 'World!'])
        );
    }
}
