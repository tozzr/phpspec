<?php

if (count($argv) == 1) {
	$path = getcwd() . "/spec";
  if (is_dir($path)) {
  	include("phpspec.php");
  	foreach (scandir($path) as $file) {
    	if ($file != "." && $file != "..") {
        include($path . "/" . $file);
    	}
		}
		global $phpSpec;
		$phpSpec->execute();
  }
}
else {
	if (is_file($argv[1])) {
		include("phpspec.php");
		include($argv[1]);
	}
}

?>