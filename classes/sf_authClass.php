<?php
class sf_authClass {
	public $db;
	public $strMessage = '';
	
	public function __construct(){
		global $arrDB;
		// setting up DB
		$this->db = new PDO('mysql:host=' . $arrDB['host']. ';dbname=' . $arrDB['db'], $arrDB['user'], $arrDB['pass']);

		//signout
		if(isset($_GET['signout']) and $_GET['signout'] == 1){
			$this->logOut();
		}

		if($this->isLoggedIn() === false){
			if(isset($_POST['username']) and $_POST['username'] != '' and
				isset($_POST['password']) and $_POST['password'] != ''
				){
				$blnOk = $this->logIn(trim($_POST['username']) , $_POST['password']);
				if($blnOk === false){
					$this->strMessage = 'Wrong username or password';
				}
			}
		}
	}
	
	public function checkToken($strToken , $strUID , $blnFromDB = false){
		if($blnFromDB){
			$strSQL = "SELECT * FROM sf_user WHERE uid = ? AND utoken = ? LIMIT 1";
			$objPrepare = $this->db->prepare($strSQL);
			$objPrepare->execute(array((int) $strUID, $strToken));
			$arrData = $objPrepare->fetchAll();
			return (isset($arrData[0]['utoken']) and $arrData[0]['utoken'] == $strToken) ? true : false;
		}else{
			return (isset($_SESSION['token']) and $_SESSION['token'] == $strToken)? true : false;
		}
	}
	
	public function isLoggedIn(){
		return (isset($_SESSION['token']) and $_SESSION['token'] != '')? true : false;
	}
	
	public function logIn($strUsername , $strPassword){
		global $strSalt;
		$blnOk = false;
		$strSQL = "SELECT * FROM sf_user WHERE md5(uname)='" . md5($strUsername) . "' AND upass='" . md5($strSalt . trim($strPassword)) . "' LIMIT 1";

		foreach( $this->db->query($strSQL) as $row){
			if(isset($row['uname']) and $row['uname'] != ''){
			// setting the new token
				$blnOk = true;
				$strToken = md5(date('YmdHis') . $strSalt);
				$_SESSION['token'] = $strToken;
				$_SESSION['uid'] = $row['uid'];
				$_SESSION['ufullname'] = $row['ufullname'];
				$_SESSION['umail'] = $row['umail'];
				$_SESSION['uname'] = $row['uname'];

				$strSQL = "UPDATE sf_user SET utoken = '" . $strToken . "' , lastseen = now() WHERE uid = '" . $row['uid'] . "' LIMIT 1";
				$this->db->query($strSQL);
			}
		}
		return $blnOk;
	}

	public function logOut(){
		foreach($_SESSION as $key=>$row){
			unset($_SESSION[$key]);
		}
		return true;
	}

}