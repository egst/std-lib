<?php declare(strict_types = 1);

// Taken from https://www.php-fig.org/psr/psr-4/examples/
spl_autoload_register(function ($class) {
    $prefix = 'Std\\';
    $baseDir = __DIR__ . '/../';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0)
        return;
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
        if (method_exists($class, '__init'))
            $class::__init();
    }
});
