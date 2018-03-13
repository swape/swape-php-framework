<?php

class PDOWrapperClass
{
    public $strUrl = '';
    public $user = '';
    public $pass = '';

    public function __construct($strUrl, $user, $pass)
    {
        $this->strUrl = $strUrl;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function pdo()
    {
        return new PDO($this->strUrl, $this->user, $this->pass);
    }

    public function query($strSQL, $arrParams = false)
    {
        $db = $this->pdo();
        $sth = $db->prepare($strSQL);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        if ($arrParams) {
            foreach ($arrParams as $i) {
                $sth->bindParam($i['name'], $i['value']);
            }
        }
        $sth->execute();
        return ['error'=> $sth->errorInfo(),'lastId'=> $db->lastInsertId(),'data'=> $sth->fetchAll()];
    }
}
