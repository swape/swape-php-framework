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
            $sql = "INSERT INTO test_table SET mytext = :mytext ";
            $arr_params = [
                ['name' => ':mytext', 'value' => $arr['data']['req']['myvar']],
            ];

            $result = $arr['db']->query($sql, $arr_params);
            return ['myvar' => $result];
        } else {
            return ['method' => $arr['method'], 'data' => $arr['data']];
        }
    }
}
