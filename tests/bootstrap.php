<?php

declare(strict_types=1);

$candidates = [
    dirname(__DIR__).'/vendor/autoload.php',
    dirname(__DIR__, 3).'/vendor/autoload.php',
];

foreach ($candidates as $autoload) {
    if (is_file($autoload)) {
        require $autoload;

        return;
    }
}

fwrite(STDERR, "Unable to locate Composer autoload.php for gpio-framework tests.\n");
exit(1);
