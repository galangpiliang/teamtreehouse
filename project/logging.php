<?php


// Makes sure we are logging errors
ini_set("log_errors", 1);
// Tell php where to log the errors
ini_set("error_log", "php-error.txt");

$error_levels = array("E_ALL","E_NOTICE","E_STRICT","E_DEPRECATED","E_ERROR","E_WARNING","E_PARSE");

foreach ($errors as $error){
  echo $error."<br />";
}