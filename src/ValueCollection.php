<?php
declare(strict_types=1);

namespace Sapien;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

abstract class ValueCollection extends ValueObject implements ArrayAccess, Countable, IteratorAggregate
{
    public function __construct(protected readonly array $items = [])
    {
    }

    public function count() : int
    {
        return count($this->items);
    }

    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function isEmpty() : bool
    {
        return empty($this->items);
    }

    public function offsetExists(mixed $key) : bool
    {
        return isset($this->items[$key]);
    }

    public function offsetGet(mixed $key) : mixed
    {
        return $this->items[$key];
    }

    public function offsetSet(mixed $key, mixed $value) : void
    {
        throw new Exception(get_class($this) . ' is readonly');
    }

    public function offsetUnset(mixed $key) : void
    {
        throw new Exception(get_class($this) . ' is readonly');
    }
}
