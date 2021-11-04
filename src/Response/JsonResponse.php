<?php
declare(strict_types=1);

namespace Sapien\Response;

use Sapien\Response;

class JsonResponse extends Response
{
    private int $flags = 0;

    private int $depth = 512;

    public function setContent(mixed $content) : static
    {
        return $this->setJson($content);
    }

    public function setJson(
        mixed $value,
        string $type = null,
        int $flags = null,
        int $depth = null,
    ) : static
    {
        $this->setHeader('content-type', $type ?? 'application/json');
        $this->flags = $flags ?? $this->flags;
        $this->depth = $depth ?? $this->depth;
        return parent::setContent($value);
    }

    protected function sendContent() : void
    {
        echo json_encode(
            $this->getContent(),
            $this->flags,
            ($this->depth <= 1) ? 1 : $this->depth
        );
    }
}
