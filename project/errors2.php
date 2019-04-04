<?php

$error_levels = array("E_ALL", "E_NOTICE", "E_WARNING", "E_ERROR",
                      "E_STRICT", "E_DEPRECATED", "E_PARSE");

foreach ($error_levels as $error){
  echo $error."<br />";
    
}