<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

$arrVars['some_info_from_config'] = 'hello';

// Return db obj
function getDbObj()
{
    // se documentation on http://php.net/manual/en/book.pdo.php
    // mysql
    return new PDO('mysql:host=localhost;dbname=testdb', 'root', 'root');
    // postgresql
    // return new PDO('pgsql:host=192.168.137.1;port=5432;dbname=anydb', 'anyuser', 'pw');
    // no db
    // return false;
}
