<?php

class mainClass{
	public $intPerm = 0; // 0 = all, 1 only for signed in useres and 2 for custom permission basesd on user id
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