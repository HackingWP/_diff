<?php

// Find `wp-content/themes` dir
define('THEMES_ROOT', dirname(dirname(__FILE__)));

// Create the diff converter
$_diff = new UnderscoreDiff;

// Combines all LESS files into one:
$_diff->combineLESS = true;

// Skip ALLCAPS files
$_diff->skipALLCAPSFiles = true;

// Set name:
$_diff->name = 'underscores';

// Set destination:
$_diff->destination = THEMES_ROOT.'/'.$_diff->name;

// Set source:
$_diff->source = THEMES_ROOT.'/_s-LESS';

// Output:
?><style>u { color: red; font-weight: bold; text-decoration: none; }</style><pre><?php

$_diff->run();
