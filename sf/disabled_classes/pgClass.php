<?php
/*
 pgClass.php 2013 Alireza Balouch
 v0.1 2013.08.29
*/
class pgClass{
	public $objLink = '' ;
	public $intQ = 0;
		
	public function init( $strUser, $strPass, $strDB, $strHost = 'localhost' , $strPort = 5432 ){	
		$strCon = "host=" . $strHost ." port=" . $strPort . " dbname=" . $strDB . " user=" . $strUser ." password=" . $strPass;
		$dbconn = pg_connect($strCon);
		$stat = pg_connection_status($dbconn);
		if ($stat === PGSQL_CONNECTION_OK){
			$this->objLink = $dbconn;
		}else{
			echo 'Connection status bad';
		}
	}
	
	public function status(){
		return pg_connection_status($this->objLink);
	}

	public function get($strSQL){
		$arrData = array();
        try{
            $objRes = pg_query($this->objLink, $strSQL );
			$arrRet = pg_fetch_array($objRes, NULL, PGSQL_ASSOC);
			pg_free_result($objRes);
        }catch(Exception $e){
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
        $this->intQ++;
		return $arrRet;
	}
	
	public function set($strSQL){
		$this->intQ++;
        try{
        	$objRes = pg_query($this->objLink, $strSQL );
			return pg_last_oid($objRes);
        }catch(Exception $e){
            echo 'Caught exception: ' . $e->getMessage() . "\n";
            return -666;
        }
	}
	
	public function rens($strInput){ // cleaning the input
		return pg_escape_string($this->objLink , trim($strInput));
	}
	
	public function close(){ // in case you need that
		pg_close($this->objLink);
	}

}
?>