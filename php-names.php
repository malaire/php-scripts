<?php
# 
# DESCRIPTION
#   Shows various names used in PHP.
#   - variables, functions, constants, classes, ...
#
# REQUIREMENTS
#   - PHP 5.5
#
# LICENSE
#   Copyright (c) 2017 Markus Laire
#
#   Permission is hereby granted, free of charge, to any person obtaining a copy
#   of this software and associated documentation files (the "Software"), to
#   deal in the Software without restriction, including without limitation the
#   rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
#   sell copies of the Software, and to permit persons to whom the Software is
#   furnished to do so, subject to the following conditions:
#
#   The above copyright notice and this permission notice shall be included in
#   all copies or substantial portions of the Software.
#
#   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
#   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
#   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
#   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
#   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
#   FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
#   IN THE SOFTWARE.
#

# DISPLAY - HEADER
echo <<<END
<!DOCTYPE html>
<html><head>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
  <title>PHP names</title>
</head><body>
<h1>PHP names</h1>
END;

$all = [];

# VARIABLES
# - NOTE: this is first so I don't have many variables to skip
foreach (get_defined_vars() as $name => $value) {
  # skip my variables
  if ($name === 'all' || $name === 'name' || $name === 'value') continue;
  $all[] = [$name, 'variable'];
}

# FUNCTIONS
$fn = get_defined_functions();
foreach ($fn['internal'] as $name) {
  $all[] = [$name, 'function'];
}

# CONSTANTS 
foreach (get_defined_constants(true) as $category => $x) {
  foreach ($x as $name => $value) {
    $all[] = [$name, "constant, $category"];
  }
}

# CLASSES
foreach (get_declared_classes() as $name) {
  $all[] = [$name, 'class'];
}

# INTERFACES  
foreach (get_declared_interfaces() as $name) {
  $all[] = [$name, 'interface'];
}

# TRAITS
foreach (get_declared_traits() as $name) {
  $all[] = [$name, 'trait'];
}

# KEYWORDS
# - http://php.net/manual/en/reserved.keywords.php
foreach ([
  '__halt_compiler()', 'abstract', 'and', 'array()', 'as', 'break', 'case',
  'catch', 'class', 'clone', 'const', 'continue', 'declare', 'default',
  'die()', 'do', 'echo', 'else', 'elseif', 'empty()', 'enddeclare', 'endfor',
  'endforeach', 'endif', 'endswitch', 'endwhile', 'eval()', 'exit()',
  'extends', 'final', 'for', 'foreach', 'function', 'global', 'if',
  'implements', 'include', 'include_once', 'instanceof', 'interface',
  'isset()', 'list()', 'new', 'or', 'print', 'private', 'protected', 'public',
  'require', 'require_once', 'return', 'static', 'switch', 'throw', 'try',
  'unset()', 'use', 'var', 'while', 'xor'
] as $name) {
  $all[] = [$name, 'keyword'];
}
$all[] = ['callable'     , 'keyword (PHP 5.4)'];
$all[] = ['finally'      , 'keyword (PHP 5.5)'];
$all[] = ['goto'         , 'keyword (PHP 5.3)'];
$all[] = ['insteadof'    , 'keyword (PHP 5.4)'];
$all[] = ['namespace'    , 'keyword (PHP 5.3)'];
$all[] = ['trait'        , 'keyword (PHP 5.4)'];
$all[] = ['yield'        , 'keyword (PHP 5.5)'];

# MAGIC CONSTANTS
# - http://php.net/manual/en/reserved.keywords.php
# - http://php.net/manual/en/language.constants.predefined.php
$all[] = ['__CLASS__'    , 'constant, magic'];
$all[] = ['__DIR__'      , 'constant, magic (PHP 5.3)'];
$all[] = ['__FILE__'     , 'constant, magic'];
$all[] = ['__FUNCTION__' , 'constant, magic'];
$all[] = ['__LINE__'     , 'constant, magic'];
$all[] = ['__METHOD__'   , 'constant, magic'];
$all[] = ['__NAMESPACE__', 'constant, magic (PHP 5.3)'];
$all[] = ['__TRAIT__'    , 'constant, magic (PHP 5.4)'];

# RESERVED WORDS
# - http://php.net/manual/en/reserved.other-reserved-words.php
$all[] = ['int'          , 'reserved word (PHP 7.0)'];
$all[] = ['float'        , 'reserved word (PHP 7.0)'];
$all[] = ['bool'         , 'reserved word (PHP 7.0)'];
$all[] = ['string'       , 'reserved word (PHP 7.0)'];
$all[] = ['true'         , 'reserved word (PHP 7.0)'];
$all[] = ['false'        , 'reserved word (PHP 7.0)'];
$all[] = ['null'         , 'reserved word (PHP 7.0)'];
$all[] = ['void'         , 'reserved word (PHP 7.1)'];
$all[] = ['iterable'     , 'reserved word (PHP 7.1)'];
$all[] = ['resource'     , 'reserved word, soft (PHP 7.0)'];
$all[] = ['object'       , 'reserved word, soft (PHP 7.0)'];
$all[] = ['mixed'        , 'reserved word, soft (PHP 7.0)'];
$all[] = ['numeric'      , 'reserved word, soft (PHP 7.0)']; 

# SORT
usort($all, function ($a, $b) {
  return strcasecmp($a[0], $b[0]);
});

# GET MAXLEN
$maxlen = 0;
foreach ($all as list($name, $info)) {
  if (strlen($name) > $maxlen) $maxlen = strlen($name);
}  

# DISPLAY - TOC
echo "<ol>\n";
echo "  <li><a href='#short'>Short</a>\n";
echo "  <li><a href='#all'>All</a> (";
foreach (str_split('_ABCDEFGHIJKLMNOPQRSTUVWXYZ') as $char) {
  echo "<a href='#all-{$char}'>{$char}</a> ";
}
echo ")\n";
echo "</ol>\n";

# DISPLAY - SHORT
echo "<h2><a name='short'></a>Short</h2>\n";
echo "<pre>\n";
foreach ($all as list($name, $info)) {
  if (strlen($name) <= 3) {
    printf("%-{$maxlen}s %s\n", $name, $info);
  }
}
echo "</pre>\n";

# DISPLAY - ALL
echo "<h2><a name='all'></a>All</h2>\n";
echo "<pre>\n";
$prev_first_char = null;
foreach ($all as list($name, $info)) {
  $first_char = strtoupper(substr($name, 0, 1));
  
  if ($prev_first_char !== $first_char) {
    echo "</pre><h3><a name='all-{$first_char}'></a>{$first_char}</h3><pre>\n";
    $prev_first_char = $first_char;
  }
  printf("%-{$maxlen}s %s\n", $name, $info);
}
echo "</pre>\n";

# DISPLAY - FOOTER
echo "</body></html>\n";
