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
        $type = $type ?? $this->getHeader('content-type') ?? 'application/json';
        $this->setHeader('content-type', $type);
        $this->flags = $flags ?? $this->flags;
        $this->depth = $depth ?? $this->depth;
        return parent::setContent($value);
    }

    public function setJsonFlags(int $flags) : static
    {
        $this->flags = $flags;
        return $this;
    }

    public function getJsonFlags() : int
    {
        return $this->flags;
    }

    public function setJsonDepth(int $depth) : static
    {
        $this->depth = $depth;
        return $this;
    }

    public function getJsonDepth() : int
    {
        return $this->depth;
    }

    protected function sendContent() : void
    {
        echo json_encode(
            $this->getContent(),
            $this->flags,
            $this->depth <= 1 ? 1 : $this->depth,
        );
    }
}
