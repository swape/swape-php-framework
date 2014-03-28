<?php

class sf_tools{   
    public function rot13encrypt($str){
        return str_rot13(base64_encode($str));
    }

    public function rot13decrypt($str){
        return base64_decode(str_rot13($str));
    }
 
    static public function sizeConvert($intSize){
        $strUnit = array('b','kb','mb','gb','tb','pb');
        return @round($intSize/pow(1024,($i=floor(log($intSize,1024)))),2).' '.$strUnit[$i];
    }
    
    /*
     * accepts array,object,string,boolean
	 * $arrSettings['class'] = '';
	 * $arrSettings['function'] = '';
	 * $arrSettings['filename'] = '';
	 * $arrSettings['ttl'] = '';
	 * $arrSettings['arg'] = ''; // array of arguments
	 *
     */
    public function ccache($arrSettings){
        // custom cache by Alireza Balouch v1.5
        $strCCpath = '/tmp/';
		$arrParameter = (isset($arrSettings['arg']) and is_array($arrSettings['arg']))? $arrSettings['arg'] : '';
		$intTTL = (isset($arrSettings['ttl']) and $arrSettings['ttl'] != '') ? $arrSettings['ttl'] : '10 min ago';
		$arrCallable = array($arrSettings['class'] , $arrSettings['function']);
		$strFilename = (isset($arrSettings['filename']) and $arrSettings['filename'] != '') ? $arrSettings['filename'] : $arrSettings['class'] . $arrSettings['function'];
		
        $strFile = $strCCpath . 'ccache_' . md5($strFilename);
        if(file_exists($strFile) and filemtime($strFile) >= strtotime($intTTL) ){
            $strReturn = unserialize(file_get_contents($strFile));
        }
        else{
            if(is_callable($arrCallable )){
                $strReturn = ($arrParameter == '')? call_user_func($arrCallable) : call_user_func_array($arrCallable , $arrParameter);
                @file_put_contents($strFile, serialize($strReturn));
                sf_tools::ccache_Clean(); // this should be from cron
            }else{
                $strReturn = 'not callable';
            }
        }    
        return $strReturn;
    }
    
    public function ccache_Clean(){
        // mkdir mem
        // mount -t ramfs -o size=250M ramfs /tmp/mem
        // chown www-data mem
        
        $strCCpath = '/tmp/';
        $arrDir = @scandir($strCCpath);
        foreach($arrDir as $row){
            if(substr($row, 0 ,1) == '.' OR substr($row, 0 ,7) != 'ccache_'){continue;} // skip the . and non ccache_
            $strFile = $strCCpath . $row ;
            if(@filemtime($strFile) <= strtotime('30 min ago')){ // 30 min ago is max age
                @exec('mv ' . $strFile . ' ' . $strCCpath . 'old &');
            }
        }
    }
   
    public static function dumpThis($data , $blnTextarea = false){
        $out = '';
        if ($data != ''){
            ob_start();
            echo ($blnTextarea)? '<textarea width="100%" height="300" >' : '<pre>';
            print_r($data);
            echo ($blnTextarea) ? '</textarea>':'</pre>';
            $out = ob_get_contents();
            ob_end_clean();
        }
        return $out;
    }
}
