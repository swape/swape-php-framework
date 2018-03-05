<?php

class sf_apiClass
{

    public function sf_index($arr)
    {

      $strSQL = 'SELECT * FROM `test_table` ';
      $objPrepare = $arr['db']->prepare($strSQL);
      $objPrepare->setFetchMode(PDO::FETCH_ASSOC);
      $objPrepare->execute();
      $arrData = $objPrepare->fetchAll();

      return $arrData;
    }

    public function sf_insert($arr)
    {
      if($arr['method'] == 'POST'){
        $strSQL = "INSERT INTO test_table SET text = :mytext ";

        $sth = $arr['db']->prepare($strSQL);
        $sth->bindParam(':mytext', $arr['data']['req']['myvar']);
        $sth->execute();
        $error = $sth->errorInfo();
        if(isset($error[0]) && $error[0] == '00000'){
          return ['id'=> $arr['db']->lastInsertId()];
        }else{
          return ['error'=> $sth->errorInfo()];
        }

      }else{
        return ['method'=> $arr['method'] ];
      }

    }

}
