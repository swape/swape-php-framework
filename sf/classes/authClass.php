<?php
/*
 authClass.php 2008 Alireza Balouch
 v1.1 2014.02.06

*/
class authClass{
    public function __construct(){
    	global $strWebPath;
    	if(@$_GET['logout'] == 1){
	    	unset($_SESSION['uid']);
			unset($_SESSION['utoken']);
			foreach($_SESSION as $key=>$row){
				 unset($_SESSION[$key]);
			}
			header('Location: ' . $strWebPath );
    	}
    	$this->logInUser();
    }

    public function isLoggedIn(){
    	global $db;
    	$blnRet = false;
	    if(@$_SESSION['uid'] != '' and $_SESSION['utoken'] != ''){
		    $strSQL = "SELECT * FROM sf_user WHERE uid = '" . $db->clean($_SESSION['uid']). "' LIMIT 1";
		    $arrData = $db->get($strSQL);
		    if( @$arrData[0]['utoken'] == @$_SESSION['utoken'] ){
			    $blnRet = true;
		    }
	    }
	    return $blnRet;
    }

    public function logInUser(){
    	global $db,$strSalt;
	    if(@$_POST['uname'] != '' and @$_POST['upass'] != '' and @$_SESSION['uid'] == ''){
		    $strSQL = "SELECT * FROM sf_user WHERE md5(uname) = '" . md5($db->clean($_POST['uname'])) . "' AND upass = '" . md5($db->clean($_POST['upass']) . $strSalt) . "' LIMIT 1";
		    $arrData = $db->get($strSQL);

		    if(@$arrData[0]['uname'] == @$_POST['uname']){
		    	$strUtoken = md5( date('Ym') . @$arrData[0]['uname'] . @$_SERVER['REMOTE_ADDR'] );
			    $_SESSION['uid'] = @$arrData[0]['uid'];
			    $_SESSION['utoken'] = $strUtoken;
			    $_SESSION['ufullname'] = @$arrData[0]['ufullname'];
			    $_SESSION['umail'] = @$arrData[0]['umail'];
			    $_SESSION['lastseen'] = @$arrData[0]['lastseen'];
			    $_SESSION['login_message'] = '';
			    $strSQL = "UPDATE sf_user SET utoken = '" . $strUtoken . "' , lastseen = now() WHERE uid = '" . @$arrData[0]['uid'] . "' ";
			    $db->set($strSQL);
		    }else{
			    unset($_SESSION['uid']);
			    unset($_SESSION['utoken']);
			    foreach($_SESSION as $key=>$row){
				    unset($_SESSION[$key]);
				}
                // can give some feedback on wrong username or password here from the theme.
                $_SESSION['login_message'] = 'Wrong username or password';
		    }
	    }
    }

    public function logOut(){
    	$strReturn = '';
	    if(@$_SESSION['uid'] != '' and @$_SESSION['utoken'] != ''){
		    $strReturn = '<a href="?logout=1" class="btn"><i class="fa fa-power-off"></i> Logout</a>';
	    }
	    return $strReturn;
    }

    public function getLoginForm(){
	    $strReturn = '<div class="logindiv">' .@$_SESSION['login_message'] . '
<form action="" method="post" id="loginform">
    <div>
	    <label>Username: </label>
        <input name="uname" type="text" id="inputUser" placeholder="Username">
     </div>
     <div>
        <label>Password: </label>
        <input name="upass" type="password" id="inputPassword" placeholder="Password">
     </div>
     <div><button type="submit" class="button">Sign in</button></div>
</form></div>';
	    foreach($_SESSION as $key=>$row){
				 unset($_SESSION[$key]);
		}
	    return $strReturn;
    }
}
?>