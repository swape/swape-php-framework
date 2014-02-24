<?php
/*
 mysqlClass.php 2008 Alireza Balouch
 v1.0 2008.11.27
*/
class mysqlClass{

	private $strHost = '' ;
	private $strUser = '' ;
	private $strPass = '' ; 
	private $strDB   = '' ;
	public $objLink = '' ;
	public $intQ = 0;
	public  $lastError = '';
		
	public function init( $strUser, $strPass, $strDB, $strHost = 'localhost' ){	
		$this->strUser = $strUser;
		$this->strPass = $strPass;
		$this->strDB   = $strDB;
		$this->strHost= $strHost ;
		$this->objLink = mysql_connect($this->strHost, $this->strUser, $this->strPass ) or die('Could not connect: ' . mysql_error( $this->objLink));
		mysql_select_db( $this->strDB ,$this->objLink) or die('Could not select database: ' .  mysql_error($this->objLink));
	}

	public function get($strSQL){
		$arrData = array();
                try{
                    $mixResult = mysql_query( $strSQL , $this->objLink);
                    if ($mixResult == false){ throw new Exception('SQL error: ' . mysql_error($this->objLink) ); }
                    while ($line = mysql_fetch_array($mixResult, MYSQL_ASSOC)){
                            $arrData[] = $line;
                    }
                    mysql_free_result($mixResult);
                }catch(Exception $e){
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                    sf_tools::sf_logg(23 ,'mysql Get error: ' . $e->getMessage());
                }
        $this->intQ++;
		return $arrData;
	}
	
	public function set($strSQL){
		$this->intQ++;
        try{
			$mixResult = mysql_query( $strSQL , $this->objLink);
			if ($mixResult == false){throw new Exception('SQL error: ' . mysql_error($this->objLink));}
			return @mysql_insert_id($this->objLink);
        }catch(Exception $e){
            sf_tools::sf_logg(23 ,'mysql Set error: ' . $e->getMessage());
            $lastError = $e->getMessage();
            return -666;
        }
                
	}
	
	public function rens($strInput){
		return mysql_real_escape_string(trim($strInput));
	}
	
	public function filedNames($strTableName){
		$arrReturn = array();
		$strSQL = "SHOW COLUMNS FROM " . $strTableName ." ";
		$mixResult = mysql_query( $strSQL , $this->objLink);
		if (mysql_num_rows($mixResult) > 0) {
    		while ($row = mysql_fetch_array($mixResult , MYSQL_ASSOC)) {
				$arrReturn[] = $row;
   			}
		}
		return $arrReturn;
	}
}
?>