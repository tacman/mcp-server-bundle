<?php

declare(strict_types=1);

use Symfony\Component\ErrorHandler\ErrorHandler;

require \dirname(__DIR__) . '/vendor/autoload.php';

/**
 * @see https://github.com/symfony/symfony/issues/53812
 */
set_exception_handler([new ErrorHandler(), 'handleException']);

$varDirectory = __DIR__ . '/TestApp/var';

/**
 * Recursively removes a directory and all its contents
 */
function removeDirectory(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }

    $files = array_diff(scandir($dir), ['.', '..']);

    foreach ($files as $file) {
        $path = $dir . \DIRECTORY_SEPARATOR . $file;

        if (is_dir($path)) {
            removeDirectory($path);
        } else {
            unlink($path);
        }
    }

    rmdir($dir);
}

if (is_dir($varDirectory) === true) {
    removeDirectory($varDirectory);
}
