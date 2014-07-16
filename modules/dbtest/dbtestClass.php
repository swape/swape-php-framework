<?php

class dbtestClass{
	public $db;
	
	public function sf_Index(){
		$strReturn = '';
		// getting some usernames from DB
		foreach($this->db->query('SELECT * FROM sf_user') as $row) {
 			$strReturn .= $row['uname'].'<br/>';
		}
		return $strReturn;
	}

	public function __construct(){
		global $arrDB;
		// setting up DB
		$this->db = new PDO('mysql:host=' . $arrDB['host']. ';dbname=' . $arrDB['db'], $arrDB['user'], $arrDB['pass']);
	}

}
