<?php
declare(strict_types=1);

namespace Sapien\Response;

use Sapien\ValueObject;

class Cookie extends ValueObject
{
    public readonly string $func;

    public readonly string $value;

    public readonly array $options;

    public function __construct(
        string $func,
        string $value,
        array $options,
    ) {
        $this->func = $func;
        $this->value = $value;

        foreach ($options as $key => $value) {
            $this->parseOption($options, $key, $value);
        }

        $this->options = $options;
    }

    protected function parseOption(
        array &$options,
        mixed $key,
        mixed $value
    ) : void
    {
        if ($value === null) {
            unset($options[$key]);
            return;
        }

        switch ($key) {
            case 'expires';
                settype($value, 'int');
                $options[$key] = $value;
                return;

            case 'path':
            case 'domain':
            case 'samesite':
                settype($value, 'string');
                $options[$key] = $value;
                return;

            case 'secure':
            case 'httponly':
                settype($value, 'bool');
                $options[$key] = $value;
                return;

            default:
                unset($options[$key]);
                return;
        }
    }
}
