<?php
// Report simple running errors
error_reporting(E_ALL);
// Make sure they're on screen
ini_set('display_errors', 1);
// HTML formatted errors
ini_set("html_errors", 1);

// Fatal
//@require "non_existent_file";

// Warning
@include "non_existent_file";

// Warning
//$my_file = @file('non_existent_file') or die("Failed opening file");

function error_function($error) {
    echo $bad;
    // Notice
    return $error;
}

// Warning
echo @error_function();

echo "End of File.";