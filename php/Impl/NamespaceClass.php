<?php declare(strict_types = 1);

namespace Std\Impl;

class NamespaceClass {

    static function __init (): void {
        // Store closures for every static public method in a corresponding static property:
        $reflection = new \ReflectionClass(static::class);
        $methods    = $reflection->getMethods(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_STATIC);
        foreach ($methods as $method) if (property_exists(static::class, $method->name)) { // TODO: Does this include non-static properties?
            static::${$method->name} = $method->getClosure();
        }
    }

}
