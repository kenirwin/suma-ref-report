<?php
define ("DEBUG", false); // if DEBUG==true, display mysql query strings, variables, etc.
define ("SUMASERVER_URL", ""); // full url with no trailing slash, e.g. http://www.example.com/sumaserver, see note about sumaserver security in README.md file
define ("SUMA_REPORTS_URL", ""); // full url with no trailing slash, e.g. http://www.example.com/suma/analysis/reports, see note about sumaserver security in README.md file
define ("MAX_API_QUERIES", 15); // if it ever takes more than this many queries to download all the Suma data in batches of 10,000 the script will quit
$default_init   = 1; //any initiative you choose
$eligible_inits = array(1); //e.g. array(1,3)
?>