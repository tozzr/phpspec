<?php
include("phpspec.php");
	
$path = "";

if (count($argv) == 1)
	$path = getcwd() . "/spec";
else
	$path = $argv[1];

if (is_dir($path))
	foreach (scandir($path) as $file) {
  	if ($file != "." && $file != "..") {
      include($path . "/" . $file);
		}
	}
else if (file_exists($path))
	include($path);
else {
	echo $path . " not found" . PHP_EOL;
  exit();
}

global $phpSpec;
$phpSpec->evaluate();

?>