<?php

class dbtestClass{
    public $intPerm = 1;
    public function sf_Index(){
        return '<h1>Restricted area</h1>You can see this if you have signed in.';
    }
    
    public function __construct(){
	    global $strMenu , $strThisWebModule ,$db;
	    if(@$_SESSION['uid'] != ''){
	        $strSQL = "SELECT * FROM sf_menu WHERE uid = '" . $db->clean($_SESSION['uid']). "' ";
	        $arrData = $db->get($strSQL);
	        foreach($arrData as $row){
                $strMenu .= '<a href="' . $strThisWebModule . $row['module_name'] . '">' . $row['module_title']. '</a>';
            }
	    }
	}
    
    public function sf_users(){
        global $db;
        $strSQL = "SELECT uid,ufullname FROM sf_user";
        $arrData = $db->get($strSQL);
        $strReturn = '<h1>Users</h1>';
        foreach($arrData as $row){
            $strReturn .= '<div><b>' . $row['ufullname'] . '</b>: ' . $row['uid']. '</div>';
        }
        return $strReturn;
    }

}
