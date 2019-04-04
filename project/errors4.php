<?php


// Report simple running errors
error_reporting(E_ALL);
// Make sure they're on screen
ini_set('display_errors', 1);
// HTML formatted errors
ini_set("html_errors", 1);

// Do some errors

// Notice
var_dump(5 + $nope);

// $wrestler= new stdClass();
// Warning
$wrestler->name = 'Hulk Hogan';

// Strict
class Foo
{
    static public function bar() {}
    static public function nope() {}
}
Foo::bar();

// Error

Foo::nope();

echo "We'll never get here.";