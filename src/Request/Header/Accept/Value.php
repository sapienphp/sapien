<?php
declare(strict_types=1);

namespace Sapien\Request\Header\Accept;

use Sapien\Request;
use Sapien\ValueObject;

class Value extends ValueObject
{
    static public function newArray(?string $header = null) : array
    {
        if ($header === null) {
            return [];
        }

        $accepts = static::parse($header);

        foreach ($accepts as $key => $args) {
            $accepts[$key] = new static(...$args);
        }

        return $accepts;
    }

    static protected function parse(string $header) : array
    {
        if (trim($header) === '') {
            return [];
        }

        $buckets = [];
        $values = explode(',', $header);

        foreach ($values as $value) {
            $pairs = explode(';', $value);
            $value = $pairs[0];
            unset($pairs[0]);
            $params = [];

            foreach ($pairs as $pair) {
                $param = [];
                preg_match(
                    '/^(?P<name>.+?)=(?P<quoted>"|\')?(?P<value>.*?)(?:\k<quoted>)?$/',
                    $pair,
                    $param
                );
                $params[$param['name']] = $param['value'];
            }

            $quality = '1.0';

            if (isset($params['q'])) {
                $quality = $params['q'];
                unset($params['q']);
            }

            $buckets[$quality][] = [
                'value' => trim($value),
                'quality' => $quality,
                'params' => $params
            ];
        }

        // reverse-sort the buckets so that q=1 is first and q=0 is last,
        // but the values in the buckets stay in the original order.
        krsort($buckets);

        // flatten the buckets back into the return array
        $array = [];

        foreach ($buckets as $q => $bucket) {
            foreach ($bucket as $spec) {
                $array[] = $spec;
            }
        }

        // done
        return $array;
    }

    public function __construct(
        public readonly string $value,
        public readonly string $quality,
        public readonly array $params,
    ) {
    }
}
