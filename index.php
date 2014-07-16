<?php
session_start();
require(__DIR__ . '/config.php');

// finding the script path
$strSFPath = __DIR__ .'/';

// finding web path
$strWebPath = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

// finding the module path
$arrPathQuery = explode('?' , $_SERVER['REQUEST_URI']);
$arrPath = explode('/' , $arrPathQuery[0]);
$intClassIndex = count(explode('/', $strWebPath )) - 1;
$intFunctionIndex = $intClassIndex + 1;
$intVarStart = $intFunctionIndex + 1;

$strClassName = $arrPath[$intClassIndex];
$strFunctionName = (isset($arrPath[$intFunctionIndex]) and $arrPath[$intFunctionIndex] != '') ? $arrPath[$intFunctionIndex] : 'index';

// autoload classes
function my_autoloader($class){
	global $strSFPath;
	include $strSFPath . 'classes/' . $class . '.php';
}
spl_autoload_register('my_autoloader');

//
$arrOut = [];

// getting the content
try{
	$strClassPath = $strSFPath . 'modules/'. $strClassName . '/' . $strClassName . 'Class.php';
	if(!file_exists($strClassPath)){
		// we can not find this class. lets get the main class
		$strClassPath = $strSFPath . 'modules/main/mainClass.php';
		if(!file_exists($strClassPath)){
			throw new Exception('Can not find main/mainClass.php file');
		}else{
			$strClassName = 'main';
			$strFunctionName = (isset($arrPath[$intClassIndex]) and $arrPath[$intClassIndex] != '') ? $arrPath[$intClassIndex] : 'index';
		}
	}
	// loading the class
	require $strSFPath . 'modules/'. $strClassName . '/' . $strClassName . 'Class.php';
	$strClassName .= 'Class';
	$objContent = new $strClassName;
	
	// getting the Function
	$strFunctionName = 'sf_' . $strFunctionName;
	$arrCallable = [$objContent , $strFunctionName];
	if(is_callable($arrCallable)){
		$strContent = call_user_func($arrCallable);
	}else{
		$strContent = '';
		throw new Exception('Can not find this function: ' . $strFunctionName);
	}
} catch(Exception $e){
	echo 'Class: ' . $strClassName . "<br/>";
	echo 'Function: ' . $strFunctionName . "<br/>";
	echo $e->getMessage();
}

// layout
if(		(isset($_GET['ajax']) and $_GET['ajax'] == 1)
	or	(isset($_SERVER['HTTP_X-Requested-With']) and $_SERVER['HTTP_X-Requested-With'] == 'XMLHttpRequest' )
	or	(isset($arrOut['ajax']) and $arrOut['ajax'] == 1)){
			echo $strContent;
}else{
	// --- loading theme
	$strThemeName = (isset($arrOut['theme']))?$arrOut['theme'] : $strTemplateName;
	$strThemeTemplate = (isset($arrOut['template']))?$arrOut['template'] : 'start';
	$strThemePath = $strSFPath . 'layout/' . $strThemeName;
	$strThemeWebPath = $strWebPath .'layout/' . $strThemeName;

	include($strThemePath . '/header.php');
	include($strThemePath . '/' . $strThemeTemplate . '.php');
	include($strThemePath . '/footer.php');
}
