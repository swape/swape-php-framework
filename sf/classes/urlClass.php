<?php
/*
 urlClass.php 2008 Alireza Balouch
 v3 2013.09.20
 v2b 2013.04.12
*/

class urlClass{
	public $arrVar = array();
    public $arrQuery = array();
    
	public function __construct(){
		$this->arrVar = explode('/' , @$_SERVER['REQUEST_URI'] );
		if(count($this->arrVar) != 0){
			$arrT = explode('?', $this->arrVar[count($this->arrVar) - 1]);
			$this->arrVar[count($this->arrVar) - 1] = @$arrT[0];
		}
		$a = explode('&', @$_SERVER['QUERY_STRING']);
		if(!(count($a) == 1 and $a[0] == '')){
			foreach ($a as $key=>$value){
				$b = explode("=", $value);
				$a[$b[0]] = @$b[1];
				unset($a[$key]);
			}
		}
		$this->arrQuery = $a;
	}
}