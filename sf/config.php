<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once 'PDOWrapperClass.php';

$arr_vars['some_info_from_config'] = 'hello';
// use $db_host = ''; for not using the db
$db_host = 'localhost';
$db_name = 'testdb';
$db_user = 'root';
$db_pass = 'root';

if ($db_host != '' && $db_name != '') {
  $arr_vars['db'] = new PDOWrapperClass("mysql:host=${db_host};dbname=${db_name}", $db_user, $db_pass);
}
