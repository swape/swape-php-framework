<?php

class sf_indexClass
{
    public function sf_test()
    {
        // this function is called from this path: /index/test
        return ['a'=>1234];
    }

    public function sf_index($arr)
    {
        $strSQL = 'SELECT * FROM `test_table` ';
        $objPrepare = $arr['db']->prepare($strSQL);
        $objPrepare->setFetchMode(PDO::FETCH_ASSOC);
        $objPrepare->execute();
        $arrData = $objPrepare->fetchAll();

        return ['myvar'=>$arrData];
    }

    public function sf_test2()
    {
        return ['test_2'=> 2];
    }
}
