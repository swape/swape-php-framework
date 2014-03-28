<?php
/*
 contentClass.php 2008 Alireza Balouch
 v2 2014.02.11

*/
class contentClass{
    public function getContent($strPreClass = '' , $strPreFunction = ''){
        global $strSFPath , $objURL , $strMenu;
        $strReturn = 'n/a';
        $strObjName = ($strPreClass != '')? $strPreClass : 'main';
        $strFName = ($strPreFunction != '')? $strPreFunction : 'index';
        $strClassPath = $strSFPath . 'modules/' . $strObjName .'/'. $strObjName . 'Class.php';
            
        if(file_exists( $strClassPath )){
        	require($strClassPath);
	        $strClass = $strObjName . 'Class';
            $objC = new $strClass();
            if($objC != ''){
	            $strFunction = ($strFName != '')? 'sf_' . $strFName : 'sf_Index';
	            $arrCallable = [$objC ,$strFunction];
	            if(is_callable($arrCallable)){
                    if((int) @$objC->intPerm == 0){
                        $strReturn = call_user_func($arrCallable);
                    }else{
                        $objAuth = new authClass();
                        
                        if($objAuth->isLoggedIn()){
                            if((int) @$objC->intPerm == 1){ // only for logged in users
                                $strReturn = call_user_func($arrCallable);
                            }elseif( (int)@$objC->intPerm == 2){
                                if($this->getPerm($strObjName) === true){
                                    $strReturn = call_user_func($arrCallable);
                                }else{
                                    $strReturn = '<div class="alert alert-error">You don\'t have permission to access</div>';
                                }
                            }    
                        }else{
                            $strReturn = '<div class="alert alert-error">You don\'t have permission to access</div>';
                            $strMenu = $objAuth->getLoginForm(); // omit this if you use login-form from elsewhere
                        }
                    }
	            }else{
		            $strReturn = '<div class="alert alert-error">Wrong function name in ' . $strClassPath . '</div>';
                    // 404
	            }
            }else{
	            $strReturn = '<div class="alert alert-error">Wrong Class name in ' . $strClassPath . '</div>';
                // This should never happen. but if it does, then you have to change the class name to match the file name 
            }
        }else{
	        $strReturn = '<div class="alert alert-error">This page could not be found</div>';
            // 404
        }
        return $strReturn;
    }
    
    private function getPerm($strObjName){
        global $db;
        $strSQL = "SELECT count(*) as mycount FROM sf_menu WHERE uid = '" . @$_SESSION['uid'] . "' AND module_name LIKE '" . $db->clean($strObjName). "' LIMIT 1";
        $arrData = $db->get($strSQL);
        $blnReturn = (@$arrData[0]['mycount'] == 1) ? true : false;
        return $blnReturn;
    }
}
