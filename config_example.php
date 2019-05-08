<?php
define ("DEBUG", false); // if DEBUG==true, display mysql query strings, variables, etc.
define ("SUMASERVER_URL", ""); // full url with no trailing slash, e.g. http://www.example.com/sumaserver, see note about sumaserver security in README.md file
define ("SUMA_REPORTS_URL", ""); // full url with no trailing slash, e.g. http://www.example.com/suma/analysis/reports, see note about sumaserver security in README.md file

$default_init   = 1; //any initiative you choose
$eligible_inits = array(1); //e.g. array(1,3)
?>