<?php declare(strict_types = 1);

namespace Std;

require_once('Impl/NamespaceClass.php');

class Strings extends Impl\NamespaceClass {
    /** Standard substr except that:
        * $limit may be null to indicate PHP_INT_MAX. (The PHP manual is full of lies, don't trust it.)
        * When the indices corresponding to $start and $end intersect or are out of bounds of the $target,
          empty string is returned instead of false. */
    static function sub (string $target, int $start, ?int $end = null): string {
        $end ??= PHP_INT_MAX;
        $result = substr($target, $start, $end);
        return $result === false ? '' : $result;
    }
    static $sub;

    // TODO: Maybe make this more consistend with substr - $start and $end instead of $limit. */
    /** Standard explode except that:
        * Source is first.
        * $limit may be null to indicate PHP_INT_MAX.
        * When $limit = 0, an empty array is returned instead of treating $limit a 1. */
    static function split (string $target, string $separator, ?int $limit = null): array {
        $limit ??= PHP_INT_MAX;
        if (!$limit)
            return [];
        return explode($separator, $target, $limit);
    }
    static $explode;

    /** Standard str_replace restricted to single $search and $replace as strings $replace and no $count.
        @param ?int $limit Equivalent of $limit in preg_replace. */
    static function replace (string $target, string $search, string $replace, ?int $limit = null): string {
        return self::_replace($target, $search, $replace, $limit, false, false);
    }
    static $replace;

    /** Standard str_replace restricted to single $search and $replace as strings returning a pair including replacements count.
        @param ?int $limit Equivalent of $limit in preg_replace.
        @return array The resulting string and replacements count in a [string, int] pair. */
    static function replaceCount (string $target, string $search, string $replace, ?int $limit = null): array {
        return self::_replace($target, $search, $replace, $limit, true, false);
    }
    static $replaceCount;

    /** Standard str_replace restricted to multiple $search and $replace as arrays and no $count.
        @param ?int $limit Equivalent of $limit in preg_replace. */
    static function replaceAll (string $target, array $search, array $replace, ?int $limit = null): string {
        return self::_replace($target, $search, $replace, $limit, false, false);
    }
    static $replaceAll;

    /** Standard str_replace restricted to multiple $search and $replace as arrays returning a pair including replacements count.
        @param ?int $limit Equivalent of $limit in preg_replace.
        @return array The resulting string and replacements count in a [string, int] pair. */
    static function replaceAllCount (string $target, array $search, array $replace, ?int $limit = null): array {
        return self::_replace($target, $search, $replace, $limit, true, false);
    }
    static $replaceAllCount;

    /** Standard preg_replace restricted to single $search and as strings $replace and no $count. */
    static function regexReplace (string $target, string $search, string $replace, ?int $limit = null): string {
        return self::_replace($target, $search, $replace, $limit, false, true);
    }
    static $regexReplace;

    /** Standard str_replace restricted to single $search and $replace as strings returning a pair including replacements count.
        @return array The resulting string and replacements count in a [string, int] pair. */
    static function regexReplaceCount (string $target, string $search, string $replace, ?int $limit = null): array {
        return self::_replace($target, $search, $replace, $limit, true, true);
    }
    static $regexReplaceCount;

    /** Standard str_replace restricted to multiple $search and $replace as arrays and no $count. */
    static function regexReplaceAll (string $target, array $search, array $replace, ?int $limit = null): string {
        return self::_replace($target, $search, $replace, $limit, false, true);
    }
    static $regexReplaceAll;

    /** Standard str_replace restricted to multiple $search and $replace as arrays returning a pair including replacements count.
        @param  array $replace When less elements given than in $search, the last value (or '' when empty) is repeated.
        @return array The resulting string and replacements count in a [string, int] pair. */
    static function regexReplaceAllCount (string $target, array $search, array $replace, ?int $limit = null): array {
        return self::_replace($target, $search, $replace, $limit, true, true);
    }
    static $regexReplaceAllCount;

    /** Standard strpos expept that:
        * $needle is enforced to be a string or to be converted to a string directly mimicking the post PHP8 version.
        * null is returned instead of false. The value still needs to be checked for !== null, since 0 is also == null. */
    static function find (string $target, string $needle, ?int $offset = null): ?int {
        $offset ??= 0;
        $result = strpos($target, $needle, $offset);
        return $result === false ? null : $result;
    }
    static $find;
    // TODO: findAll.

    /** Standard preg_match exept that:
        @return ?array The substring found for the pattern and its offset in a [string, int] array or null when not found. */
    static function regexFind (string $target, string $pattern, ?int $offset = null): ?array {
        return self::_find($target, $pattern, $offset, false, false, false);
    }
    static $regexFind;

    /** Standard preg_match exept that:
        @return ?array The substring found for the whole pattern followed by the substrings found for the subpatterns and their offsets
            in a ?[string, int][] array with null elements for not found optional substrings or just null when nothing found. */
    static function regexFindSub (string $target, string $pattern, ?int $offset = null): ?array {
        return self::_find($target, $pattern, $offset, true, false, false);
    }
    static $regexFindSub;

    /** Standard preg_match_all exept that:
        @return ?array The substrings found for the pattern and their offsets in a [string, int][] array. */
    static function regexFindAll (string $target, string $pattern, ?int $offset = null): array {
        return self::_find($target, $pattern, $offset, false, false, true);
    }
    static $regexFindAll;

    /** Standard preg_match_all exept that:
        @return ?array The substrings found for the pattern followed by the substrings found for the subpatterns and their offsets in a ?[string, int][][] array. */
    static function regexFindAllSub (string $target, string $pattern, ?int $offset = null): array {
        return self::_find($target, $pattern, $offset, true, false, true);
    }
    static $regexFindAllSub;

    /** Standard preg_match expept that:
        Only reports whether a matching substring was found. */
    static function regexTest (string $target, string $pattern, ?int $offset = null): bool {
        return self::_find($target, $pattern, $offset, false, true, false);
    }
    static $regexTest;

    /// Private:

    // TODO: Throw exception when invalid regex provided. (preg_last_error_msg)

    /** Standard preg_replace with the option to simulate str_replace except that when count($search) > count($replace) the last value from $replace (or '' when empty) is used instead of ''. */
    private static function _replace (string $target, $search, $replace, ?int $limit = null, bool $count = false, bool $preg = false) {
        if ($limit !== null && $limit <= 0)
            return $target;
        $replacements = 0;
        if (is_array($search) && is_array($replace) && count($search) > count($replace))
            $replace += array_fill(count($replace), count($search) - count($replace), $replace[count($replace) - 1] ?? '');
        if (!$preg && $limit === null)
            $result = str_replace($search, $replace, $target, $replacements);
        else {
            if (!$preg) {
                if (is_array($search)) foreach ($search as &$item)
                    $item = '/' . preg_quote($item, '/') . '/';
                else
                    $search = '/' . preg_quote($search, '/') . '/';
                if (is_array($replace)) foreach ($replace as &$item)
                    $item = preg_replace(['/\\\\\d/', '/\$\{\d\}/'], ['\\\\\0'], $item);
                else
                    $replace = preg_replace(['/\\\\\d/', '/\$\{\d\}/'], ['\\\\\0'], $replace);
            }
            $result = preg_replace($search, $replace, $target, $limit ?? -1, $replacements);
        }
        return $count ? [$result, $replacements] : $result;
    }

    private static function _find (string $target, string $search, ?int $offset = null, bool $sub = false, bool $test = false, bool $all = false) {
        $offset ??= 0;
        $matches = $test ? null : []; // TODO: Is this OK to specify that no matches are provided to preg_match?
        $found = $all
            ? preg_match_all($search, $target, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER | PREG_UNMATCHED_AS_NULL, $offset)
            : preg_match($search, $target, $matches, PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL, $offset);
        if ($test)
            return $found ?? false;
        if (!$found)
            return $all ? [] : null;
        if ($sub) {
            if ($all) {
                foreach ($matches as &$match) foreach ($match as [$_, &$offset]) if ($offset < 0)
                    //$offset = null;
                    $match = null;
            }
            //else foreach ($matches as [$_, &$offset]) if ($offset < 0)
                //$offset = null;
            else foreach ($matches as $match) if ($match[1] < 0)
                $match = null;
            return $matches;
        } else if ($all) {
            //var_dump($matches);
            foreach ($matches as &$match)
                $match = $match[0];
            return $matches;
        }
        return $matches[0] ?? null;
    }
}

/*
    # Replaces the following standard functions and constructs:
    ...
    substr
    explode
    str_replace
    preg_replace
    strpos
    preg_match
    preg_match_all

    sprintf
    trim
    strlen
    strtolower
    is_string
    strtoupper
    rtrim
    ucfirst
    str_repeat
    ltrim
    preg_split
    strrpos
    strtr
    urlencode
    htmlspecialchars

    # Remaining constructs to be implemented:
    addcslashes  Quote string with slashes in a C style
    addslashes  Quote string with slashes
    bin2hex  Convert binary data into hexadecimal representation
    chop  Alias of rtrim
    chr  Generate a single-byte string from a number
    chunk_split  Split a string into smaller chunks
    convert_cyr_string  Convert from one Cyrillic character set to another
    convert_uudecode  Decode a uuencoded string
    convert_uuencode  Uuencode a string
    count_chars  Return information about characters used in a string
    crc32  Calculates the crc32 polynomial of a string
    crypt  One-way string hashing
    echo  Output one or more strings
    explode  Split a string by a string
    fprintf  Write a formatted string to a stream
    get_html_translation_table  Returns the translation table used by htmlspecialchars and htmlentities
    hebrev  Convert logical Hebrew text to visual text
    hebrevc  Convert logical Hebrew text to visual text with newline conversion
    hex2bin  Decodes a hexadecimally encoded binary string
    html_entity_decode  Convert HTML entities to their corresponding characters
    htmlentities  Convert all applicable characters to HTML entities
    htmlspecialchars_decode  Convert special HTML entities back to characters
    htmlspecialchars  Convert special characters to HTML entities
    implode  Join array elements with a string
    join  Alias of implode
    lcfirst  Make a string's first character lowercase
    levenshtein  Calculate Levenshtein distance between two strings
    localeconv  Get numeric formatting information
    ltrim  Strip whitespace (or other characters) from the beginning of a string
    md5_file  Calculates the md5 hash of a given file
    md5  Calculate the md5 hash of a string
    metaphone  Calculate the metaphone key of a string
    money_format  Formats a number as a currency string
    nl_langinfo  Query language and locale information
    nl2br  Inserts HTML line breaks before all newlines in a string
    number_format  Format a number with grouped thousands
    ord  Convert the first byte of a string to a value between 0 and 255
    parse_str  Parses the string into variables
    print  Output a string
    printf  Output a formatted string
    quoted_printable_decode  Convert a quoted-printable string to an 8 bit string
    quoted_printable_encode  Convert a 8 bit string to a quoted-printable string
    quotemeta  Quote meta characters
    rtrim  Strip whitespace (or other characters) from the end of a string
    setlocale  Set locale information
    sha1_file  Calculate the sha1 hash of a file
    sha1  Calculate the sha1 hash of a string
    similar_text  Calculate the similarity between two strings
    soundex  Calculate the soundex key of a string
    sprintf  Return a formatted string
    sscanf  Parses input from a string according to a format
    str_contains  Determine if a string contains a given substring
    str_ends_with  Checks if a string ends with a given substring
    str_getcsv  Parse a CSV string into an array
    str_ireplace  Case-insensitive version of str_replace
    str_pad  Pad a string to a certain length with another string
    str_repeat  Repeat a string
    str_replace  Replace all occurrences of the search string with the replacement string
    str_rot13  Perform the rot13 transform on a string
    str_shuffle  Randomly shuffles a string
    str_split  Convert a string to an array
    str_starts_with  Checks if a string starts with a given substring
    str_word_count  Return information about words used in a string
    strcasecmp  Binary safe case-insensitive string comparison
    strchr  Alias of strstr
    strcmp  Binary safe string comparison
    strcoll  Locale based string comparison
    strcspn  Find length of initial segment not matching mask
    strip_tags  Strip HTML and PHP tags from a string
    stripcslashes  Un-quote string quoted with addcslashes
    stripos  Find the position of the first occurrence of a case-insensitive substring in a string
    stripslashes  Un-quotes a quoted string
    stristr  Case-insensitive strstr
    strlen  Get string length
    strnatcasecmp  Case insensitive string comparisons using a "natural order" algorithm
    strnatcmp  String comparisons using a "natural order" algorithm
    strncasecmp  Binary safe case-insensitive string comparison of the first n characters
    strncmp  Binary safe string comparison of the first n characters
    strpbrk  Search a string for any of a set of characters
    strpos  Find the position of the first occurrence of a substring in a string
    strrchr  Find the last occurrence of a character in a string
    strrev  Reverse a string
    strripos  Find the position of the last occurrence of a case-insensitive substring in a string
    strrpos  Find the position of the last occurrence of a substring in a string
    strspn  Finds the length of the initial segment of a string consisting entirely of characters contained within a given mask
    strstr  Find the first occurrence of a string
    strtok  Tokenize string
    strtolower  Make a string lowercase
    strtoupper  Make a string uppercase
    strtr  Translate characters or replace substrings
    substr_compare  Binary safe comparison of two strings from an offset, up to length characters
    substr_count  Count the number of substring occurrences
    substr_replace  Replace text within a portion of a string
    substr  Return part of a string
    trim  Strip whitespace (or other characters) from the beginning and end of a string
    ucfirst  Make a string's first character uppercase
    ucwords  Uppercase the first character of each word in a string
    vfprintf  Write a formatted string to a stream
    vprintf  Output a formatted string
    vsprintf  Return a formatted string
    wordwrap  Wraps a string to a given number of characters
*/
