<?php
// autoload classes
/*
    // for older php versions
	$arrDir = scandir('sf/classes/');
	foreach($arrDir as $row){
		if($row[0] != '.'){
			include('sf/classes/' . $row );
		}
	}
*/
function my_autoloader($class){
    global $strSFPath;
    include $strSFPath . 'sf/classes/' . $class . '.php';
}
spl_autoload_register('my_autoloader');
?>