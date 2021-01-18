<?php declare(strict_types = 1);

namespace Std;

require_once('Impl/NamespaceClass.php');

class ToString extends Impl\NamespaceClass {

    /** Default string conversion - simple. */
    function __invoke ($item) {
        return self::simple($item);
    }

    /** Enhanced string conversion. Returns 'closure' and 'object' for closures and other objects with no __toString method respectively. */
    static function simple ($item) {
        if (is_string($item) || is_int($item) || is_float($item) || is_resource($item))
            return (string) $item;
        if (is_bool($item))
            return $item ? 'true' : 'false';
        if (is_array($item))
            return 'array';
        if (is_object($item) && method_exists($item, '__toString'))
            return $item->__toString();
        if ($item instanceof \Closure)
            return 'closure';
        if (is_object($item))
            return 'object';
        return '';
    }
    static $simple;

    /** Standard string conversion without errors and notices. Empty string by default. */
    static function legacy ($item) {
        if (is_string($item) || is_bool($item) || is_int($item) || is_float($item) || is_resource($item))
            return (string) $item;
        if (is_array($item))
            return 'Array';
        if (is_object($item) && method_exists($item, '__toString'))
            return $item->__toString();
        return '';
    }
    static $legacy;

    /** Equivalent of var_dump. */
    static function detailed ($item): string {
        ob_start();
        var_dump($item);
        return ob_get_clean();
    }
    static $detailed;

    /** Equivalent of print_r. */
    static function humanReadable ($item): string {
        return print_r($item, true);
    }
    static $humanReadable;

    /** Equivalent of var_export. */
    static function export ($item): string {
        return var_export($item, true);
    }
    static $export;

}

/*
    # Replaces the following standard functions and constructs:
    * print_r
    * var_dump
    * var_export
    * (string)
*/
