<?php
session_start();
require('config.php');

$strSFPath = str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']);
$strWebPath = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
$intClassIndex = count(explode('/', $strWebPath )) - 1;
$intFunctionIndex = $intClassIndex + 1;
$intVarStart = $intFunctionIndex + 1;

// autoload classes
function my_autoloader($class){
    global $strSFPath;
    include $strSFPath . 'sf/classes/' . $class . '.php';
}
spl_autoload_register('my_autoloader');

// setting up db
$db = new mysqliClass($arrDB['user'], $arrDB['pass'], $arrDB['db'], $arrDB['host']);

/* URL */
$objURL = new urlClass();
$strClass = (@$objURL->arrVar[$intClassIndex] != '') ? @$objURL->arrVar[$intClassIndex] : 'main';
$strFunction = @$objURL->arrVar[$intFunctionIndex];
$strThisWebModule = $strWebPath . $strClass .'/';
$strTemplatePath = $strSFPath . 'templates/' . $strTemplateName . '/';
$strTemplateWebPath = $strWebPath . 'templates/' . $strTemplateName . '/';

/* AUTH handling */
if(@$_GET['logout'] == 1 && @$_SESSION['uid'] != ''){
    foreach($_SESSION as $key=>$row){
        unset($_SESSION[$key]);
    }
    header('Location: ' . $strWebPath );
}

/* CONTENT */
$objContent = new contentClass();
$strHead = '';
$strJs = '';
$strMenu = '';

/* MODULE */
try{
    $strContent = $objContent->getContent($strClass, $strFunction);
	if(@$_GET['ajax'] == 1 or @$_SERVER['HTTP_X-Requested-With'] == 'XMLHttpRequest'){
		echo $strContent;
	}else{
		$strCPath = $strSFPath . 'modules/' . @$strClass;
		$strCWPath = $strWebPath . 'modules/' . @$strClass;
		$strHead .= (file_exists( $strCPath . '/style.min.css'))? '<link rel="stylesheet" href="' . $strCWPath . '/style.min.css" type="text/css" />' : '';
		$strJs .= (file_exists( $strCPath . '/js.min.js'))? '<script src="' . $strCWPath . '/js.min.js" type="text/javascript"></script>' : '';		
		include($strTemplatePath . 'index.php');
	}

}catch(Exception $e){
	echo $e->getMessage();
}

// first user
//$strSQL = "INSERT INTO sf_user SET upass='" . md5('ali' . $strSalt) . "' , uname = 'ali'";
//echo $db->set($strSQL);

/* DEBUG */
/*
echo $strWebPath;
echo sf_tools::dumpThis(get_included_files() );
echo sf_tools::dumpThis($_SESSION );
echo sf_tools::sizeConvert(memory_get_usage(true));

//*/
