<?php
declare(strict_types=1);

namespace Sapien\Request\Header\Accept;

class LanguageCollection extends AcceptCollection
{
    static protected function parse(string $string) : array
    {
        $items = parent::parse($string);

        foreach ($items as &$item) {
            $parts = explode('-', $item['value']);
            $item['type'] = array_shift($parts);
            $item['subtype'] = array_shift($parts);
        }

        return $items;
    }
}
