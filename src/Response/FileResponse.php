<?php
declare(strict_types=1);

namespace Sapien\Response;

use Sapien\Exception;
use Sapien\Response;
use SplFileObject;

class FileResponse extends Response
{
    public function setContent(mixed $content) : static
    {
        if (is_string($content) || $content instanceof SplFileObject) {
            return $this->setFile($content);
        }

        throw new Exception(__CLASS__ . ' content must be string or SplFileObject');
    }

    public function setFile(
        SplFileObject|string $file,
        string $disposition = null,
        string $name = null,
        string $type = null,
        string $encoding = null,
    ) : static
    {
        if (is_string($file)) {
            $file = new SplFileObject($file, 'rb');
        }

        parent::setContent($file);

        // disposition
        $filename = rawurlencode($name ?? $file->getFilename());
        $disposition ??= 'attachment';
        $disposition = "{$disposition}; filename=\"{$filename}\"";
        $this->setHeader('content-disposition', $disposition);

        // mime type
        $type ??= $this->getHeader('content-type') ?? 'application/octet-stream';
        $this->setHeader('content-type', $type);

        // transfer encoding
        $encoding ??= $this->getHeader('content-transfer-encoding') ?? 'binary';
        $this->setHeader('content-transfer-encoding', $encoding);

        // content-length
        $size = $file->getSize();

        if ($size !== false) {
            $this->setHeader('content-length', (string) $size);
        }

        return $this;
    }

    protected function sendContent() : void
    {
        if (empty($this->getContent())) {
            throw new Exception(__CLASS__ . " has no file to send");
        }

        parent::sendContent();
    }
}
