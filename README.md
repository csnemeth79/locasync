locasync
======

##EASY and smart tool for keep language files syncronized ##

###REPO###
 
https://github.com/csnemeth79/locasync

###CONFIGURATION###

Copy config.inc.php.sample and paste it to config.inc.php on same directory.

####config tags####
    $main_directory - laguage directoty
    $prior_language - main language (only one)
    $additional_languages = array for additional languages
    $auto_fix = true = auto fix and log, false = only logging

###Usage###

  1. Open /bin directory
  2. Run: sync.bat
  3. Watch console and check out logs\log.txt
  
###REQUIREMENTS###

 - unlimited languages supported with one main language (source generation)
 - language files are case sensitive
 - language files must be valid php files
 - language files must consist only one multi-dimensional(two dimensions array)
 - any file must be only one close tag at the end of array
 - close tag -> ');'
 - sample files are:
	
	<?php
	return array(
		'success' => 'success text',
		'error' => 'error text'
		);
	?>
	
	or
	
	<?php
	return array(
		"before"           => "The :attribute must be a date before :date.",
		"between"          => array(
			"numeric" => "The :attribute must be between :min and :max.",
			"file"    => "The :attribute must be between :min and :max kilobytes.",
			"string"  => "The :attribute must be between :min and :max characters.",
			"array"   => "The :attribute must have between :min and :max items.",
		), 
	);
	?>
	
	
 



