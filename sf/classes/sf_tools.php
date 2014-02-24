<?php

class sf_tools{   
    public function rot13encrypt ($str) {
        return str_rot13(base64_encode($str));
    }

    public function rot13decrypt ($str) {
        return base64_decode(str_rot13($str));
    }
 
    static public function sizeConvert($size){
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
    
    /*
     * $arrCallable = array($moduleObj ,$strFunction );
     * accepts array,object,string,boolean
     */
    public function ccache($arrCallable ,$strFilename , $intTTL = '10 min ago'  , $arrParameter = ''){
        // custom cache by Alireza Balouch
        $strReturn = '';
        $strCCpath = '/tmp/';
        $strFile = $strCCpath . 'ccache_' . md5($strFilename);
        if(file_exists($strFile) and filemtime($strFile) >= strtotime($intTTL) ){
            $strReturn = file_get_contents($strFile);
            $strReturn = unserialize($strReturn);
        }
        else{
            if(is_callable($arrCallable )){
                if($arrParameter == ''){
                    $strReturn = call_user_func($arrCallable);
                }else{
                    $strReturn = call_user_func_array($arrCallable , $arrParameter );
                }
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
            if(substr($row, 0 ,1) == '.' OR substr($row, 0 ,7) != 'ccache_' ){continue;} // skip the . and non ccache_
            $strFile = $strCCpath . $row ;
            if(@filemtime($strFile) <= strtotime('30 min ago') ){
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
?>