<?php declare(strict_types = 1);

include_once('php/incl/autoload.php');

use Std\ToString;
use Std\Strings;

$to_string = new ToString;

//echo ToString::detailed([]);
//echo (ToString::$detailed)([]);
//print_r(array_map(ToString::$export, ['FOO', 'bar', 'Baz']));
//echo $to_string([]);
//echo $to_string->humanReadable([]);

//echo ToString::detailed(Strings::sub('abcde', 2));
//echo ToString::detailed(Strings::split('ab,c,de', ',', 2));
//echo ToString::detailed(Strings::replace('ab,c,de', ',', ';', 1));
//echo ToString::detailed(Strings::replaceAll('a,b,c-d-e', [',', '-'], [';'], 1));
//[$replaced, $count] = Strings::replaceCount('ab,c,de', ',', ';');
//echo ToString::detailed($count);
//echo ToString::detailed($replaced);
//echo ToString::detailed(Strings::find('ab,c,de', ','));
//echo ToString::detailed(Strings::regexFind('ab,c,de', '/,/'));
//echo ToString::humanReadable(Strings::regexFindAll('ab,c,de', '/,/'));
//echo ToString::humanReadable(Strings::regexFindSub('ab,c,de', '/(ab),(c)/'));
//echo ToString::humanReadable(Strings::regexFindAllSub('ab,c,ab,c', '/(ab),(c)/'));
