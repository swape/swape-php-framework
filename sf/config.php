<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once('PDOWrapperClass.php');

$arrVars['some_info_from_config'] = 'hello';
$arrVars['db'] = new PDOWrapperClass('mysql:host=localhost;dbname=testdb', 'root', 'root');
