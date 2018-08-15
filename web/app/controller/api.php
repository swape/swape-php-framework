<?php

class sf_apiClass
{
    public function sf_index($arr)
    {
        $result = $arr['db']->query('SELECT * FROM `test_table`');
        return $result;
    }

    public function sf_insert($arr)
    {
        if ($arr['method'] == 'POST' && isset($arr['data']['req']['myvar'])) {
            $strSQL = "INSERT INTO test_table SET text = :mytext ";
            $arrParams = [
                ['name'=>':mytext' , 'value'=> $arr['data']['req']['myvar']]
            ];

            $result = $arr['db']->query($strSQL, $arrParams);
            return ['myvar'=> $result];
        } else {
            return ['method'=> $arr['method'] ,'data'=> $arr['data']];
        }
    }
}
