<?php

class mainClass{
	
	public function __construct(){
	    global $strMenu , $strThisWebModule;
	    $strMenu .= '<a href="' . $strThisWebModule . 'test/">Test function</a>';
	    $strMenu .= '<a href="' . $strThisWebModule . '">Main function</a>';
	}
	
	public function sf_index(){
		$strReturn = '<h1>This is ' . __CLASS__ . '</h1>';
		$strReturn .= '<h2>function name: ' . __FUNCTION__ . '</h2>';
		$strReturn .= '<h3>From file: ' . __FILE__ . '</h3>';
		return $strReturn;
	}
    public function sf_test(){
        return 'this is test';
    }
}

?>