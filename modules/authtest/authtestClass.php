<?php
class authtestClass{
	public function sf_Index(){
		global $arrOut, $strWebPath;
		$objAuth = new sf_authClass();
		$strReturn = '';

		if($objAuth->isLoggedIn()){
			$strReturn = 'woho I´m in <a href="?signout=1">Signout</a>';
		}else{
			$arrOut['template'] = 'signinform';
			$strReturn .= $objAuth->strMessage;
		}

		if(isset($_GET['signout']) and $_GET['signout'] == 1){
			header('Location: ' . $strWebPath);
		}
		
		return $strReturn;
	}
}