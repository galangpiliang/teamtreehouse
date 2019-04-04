<?php


// Report simple running errors
error_reporting(E_ALL);
// Make sure they're on screen
ini_set('display_errors', 1);
// HTML formatted errors
ini_set("html_errors", 1);

$error_levels = array("E_ALL", "E_NOTICE", "E_WARNING", "E_ERROR",
                      "E_STRICT", "E_DEPRECATED", "E_PARSE");

foreach ($error_levels as $error){
  echo $error."<br />";
  
}