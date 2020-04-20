<?php

class SFapiClass
{
  public function sf_index($arr)
  {
    return $arr['db']->query('SELECT * FROM `test_table`');
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
