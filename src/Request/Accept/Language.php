<?php
declare(strict_types=1);

namespace Sapien\Request\Accept;

class Language extends AcceptValue
{
    static protected function parse(string $string) : array
    {
        $langs = parent::parse($string);

        foreach ($langs as &$lang) {
            $parts = explode('-', $lang['value']);
            $lang['type'] = array_shift($parts);
            $lang['subtype'] = array_shift($parts);
        }

        return $langs;
    }

    public function __construct(
        public readonly string $value,
        public readonly string $type,
        public readonly ?string $subtype,
        public readonly string $quality,
        public readonly array $params,
    ) {
    }
}
