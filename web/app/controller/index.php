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
        $result = $arr['db']->query('SELECT * FROM `test_table`');
        return ['myvar'=> $result];
    }

    public function sf_test2()
    {
        return ['test_2'=> 2];
    }
}
