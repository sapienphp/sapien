<?php
use PhpStyler\Config;
use PhpStyler\Files;
use PhpStyler\Styler;

return new Config(
    cache: __DIR__ . '/.php-styler.cache',
    files: new Files(
        __DIR__ . '/src',
    ),
    styler: new Styler(),
);
