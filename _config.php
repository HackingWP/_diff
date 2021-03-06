<?php

// Find `wp-content/themes` dir
define('THEMES_ROOT', dirname(dirname(__FILE__)));

// Create the diff converter
$_diff = new UnderscoreDiff;

// Combines all LESS files into one:
$_diff->combineLESS = true;

// Skip ALLCAPS files
$_diff->skipALLCAPSFiles = true;

// Allows to skip import of some LESS files
$_diff->skipLESSImports = [];

// Set name:
$_diff->name = 'underscores';

// Rename to overcome automatic LESS compilation problems
$_diff->stylesLESSFilename = 'style.less';

// Remove theme header from LESS output
$_diff->removeThemeHeader = true;

// Write theme header to style.css (allows use as parent theme)
$_diff->writeThemeStyleCSS = true;

// Set destination:
$_diff->destination = THEMES_ROOT.'/'.$_diff->name;

// Set source:
$_diff->source = THEMES_ROOT.'/_s-LESS';

// Output:
?><style>u { color: red; font-weight: bold; text-decoration: none; }</style><pre><?php

$_diff->run();
