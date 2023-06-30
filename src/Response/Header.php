<?php
declare(strict_types=1);

namespace Sapien\Response;

use Sapien\Exception;
use Sapien\ValueObject;
use Stringable;

/**
 * @property-read string $value
 */
class Header extends ValueObject implements Stringable
{
    protected string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new Exception('Header value cannot be blank');
        }

        $this->value = $value;
    }

    public function __get(string $key) : mixed
    {
        if ($key === 'value') {
            return $this->value;
        }

        return parent::__get($key);
    }

    public function __toString() : string
    {
        return $this->value;
    }

    public function add(Header|string $value) : void
    {
        $this->value .= ', ' . $value;
    }
}
