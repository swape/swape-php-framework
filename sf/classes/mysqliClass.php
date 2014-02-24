<?php
/*
 mysqliClass.php 2013 Alireza Balouch
 v1.1 2014.02.06
*/
class mysqliClass{
	public $objLink = '' ;
	public $intQ = 0;
	
	public function __construct($strUser, $strPass, $strDB, $strHost = 'localhost' ){
		$this->init( $strUser, $strPass, $strDB, $strHost);
	}
		
	public function init( $strUser, $strPass, $strDB, $strHost = 'localhost' ){
		if(extension_loaded('mysqli')){
			$mysqli = new mysqli($strHost, $strUser , $strPass , $strDB );
			if ($mysqli->connect_errno){
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "\n";
			}else{
				$this->objLink = $mysqli;
			}
		}else{
			echo 'No mySQLi Extention';
		}
	}

	public function get($strSQL){
		$arrData = array();
        try{
            $dbLink = $this->objLink;
            $objRes = $dbLink->query($strSQL);
            @$objRes->data_seek(0);
			while ($row = $objRes->fetch_assoc()) {
			    $arrData[] = $row ;
			}
            $objRes->free();
        }catch(Exception $e){
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
        $this->intQ++;
		return $arrData;
	}
	
	public function set($strSQL){
		$this->intQ++;
        try{
            $dbLink = $this->objLink;
            $objRes = $dbLink->query($strSQL);
			return mysqli_insert_id($dbLink);
        }catch(Exception $e){
            echo 'Caught exception: ' . $e->getMessage() . "\n";
            return -666;
        }
	}
	
	public function clean($strInput){ // cleaning the input
		return mysqli_real_escape_string($this->objLink , trim($strInput));
	}
	
	public function close(){ // in case you need that
		$this->objLink->close();
	}
	
	public function getFields($strTableName){
		$strSQL = "SELECT * FROM " . $strTableName . " LIMIT 1";
        $dbLink = $this->objLink;
        $objRes = $dbLink->query($strSQL);
		if ($objRes){
			$objInfo = mysqli_fetch_fields($objRes);
		}
		
		return json_encode($objInfo);
	}
}
?>