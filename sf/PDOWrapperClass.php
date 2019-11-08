<?php

class PDOWrapperClass
{
    public $db_url = '';
    public $user = '';
    public $pass = '';

    public function __construct($db_url, $user, $pass)
    {
        $this->db_url = $db_url;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function pdo()
    {
        return new PDO($this->db_url, $this->user, $this->pass);
    }

    public function query($sql, $arr_params = false)
    {
        $db = $this->pdo();
        $sth = $db->prepare($sql);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        if ($arr_params) {
            foreach ($arr_params as $i) {
                $sth->bindParam($i['name'], $i['value']);
            }
        }
        $sth->execute();
        return ['error' => $sth->errorInfo(), 'lastId' => $db->lastInsertId(), 'data' => $sth->fetchAll()];
    }
}