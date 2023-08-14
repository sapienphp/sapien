<?php
declare(strict_types=1);

namespace Sapien;

abstract class ValueObject
{
    public function __get(string $key) : mixed
    {
        $class = get_class($this);

        throw new Exception("{$class}::\${$key} does not exist.");
    }

    final public function __set(string $key, mixed $val) : void
    {
        $class = get_class($this);

        throw new Exception("{$class}::\${$key} does not exist.");
    }

    public function __isset(string $key) : bool
    {
        return false;
    }

    final public function __unset(string $key) : void
    {
        $class = get_class($this);

        throw new Exception("{$class}::\${$key} does not exist.");
    }

    /**
     * @return mixed[]
     */
    public function asArray() : array
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
