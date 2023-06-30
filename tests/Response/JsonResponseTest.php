<?php
declare(strict_types=1);

namespace Sapien\Response;

class JsonResponseTest extends \PHPUnit\Framework\TestCase
{
    use Assertions;

    public function test() : void
    {
        $response = new JsonResponse();
        $response->setContent(['Hello ', 'World!']);

        $response->setJsonFlags(JSON_THROW_ON_ERROR);
        $this->assertSame(JSON_THROW_ON_ERROR, $response->getJsonFlags());

        $response->setJsonDepth(128);
        $this->assertSame(128, $response->getJsonDepth());

        $this->assertSent(
            $response,
            200,
            [
                'content-type: application/json'
            ],
            (string) json_encode(['Hello ', 'World!'])
        );
    }
}
