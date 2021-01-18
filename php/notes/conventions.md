# Some of the conventions used in this library

* `PascalCase` MUST be used for classes, interfaces and traits visible to the user (to match the PSR standard).
* `PascalCase` MUST be used for file and directory names corresponding to individual classes and namespaces.
* snake_case SHOULD be used for other files and directories.
* `camelCase` MUST be used for class methods visible (to the user to match the "standard").
* `camelCase` MUST be used for class properties visible to the user (to match the method names for closure versions of the methods).
* `camelCase` MUST be used for all other constructs visible to the user (to be consistent with the previous two rules). E.g. array keys.
* `ALL_CAPS` MUST be used for constants (to be consistent with most standards and guidelines).
* `camelCase` SHOULD be used for all other constructs (even invisible to the user) as well.
* `__dUnder` MUST be used for special methods not intended to be called directly by the user (even though they might be public) (e.g. `__init`) (to match the "standard" of magic methods).
* `_sUnder` MAY be used for private methods (to distinguish from an equivalently named public method) but is not necessary.
* Name of the target type SHOULD (sometimes it's relevant to keep though) be omited from the method name.
  E.g. Strings::sub instead of substr (although Strings::substring could be ok too), Strings::replace instead of str_replace.

* "Tuples" (arrays) SHOULD be returned instead of using out parameters.
* Another function/method with diferent signature SHOULD be declared instead of using mixed type parameters and diferent behaviour depending on them.
* A nullable type with null as a fallback for an "error" or "unsuccessful" result SHOULD be returned instead of returning false.
* A nullable parameter with null as the default value SHOULD be used instead of values like -1 or PHP_INT_MAX etc.
* "Overselection" (i.e. selecting indices 0 to 5 from a string of length 3) SHOULD be clapmed to the relevant value instead of failing (e.g. returning false, null, throwing etc.).
* "Underselection" (i.e. selecting 0 elements) SHOULD return a corresponding empty value (empty string or array) instead of being implicitly interpreted as e.g. 1.

The "standard" refers to the unstated standard of the bult-in constructs and pre-defined functions and classes in PHP.
