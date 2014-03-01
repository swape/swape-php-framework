MYSQL queries
===

Using msqlClass i easy.
All you have to do in your function is to use the global **$db** object

    public function sf_users(){
        global $db;
        $strSQL = "SELECT uid,ufullname FROM sf_user";
        $arrData = $db->get($strSQL);
        $strReturn = '<h1>Users</h1>';
        foreach($arrData as $row){
            $strReturn .= '<div><b>' . $row['ufullname'] . '</b>: ' . $row['uid']. '</div>';
        }
        return $strReturn;
    }
    

You have to be sure that if something comes from the user, you have to sanetize it first.

If I know that the value passed in $_GET og $_POST is a number then I cast the value before inserting it to my SQL.

    $strSQL = "SELECT * FROM mytable WHERE user_id = '" . (int) @$_GET['user_id'] . "' ";
    
There is a another trick to avoid SQL injection. You can use md5 coded strings.
    
    $strSQL = "SELECT * FROM mytable WHERE md5(user_id) = '" . md5(@$_GET['user_id']) . "' ";

Or just use the $db->clean() function.

    $strSQL = "SELECT * FROM mytable WHERE user_id = '" . $db->clean( @$_GET['user_id'] ) . "' ";

$db->get() function returns an array with results so you don't have to deal with objects. So if you switch from mysql to postgreSQL or other dbs, then you don't have to change any code.

For updates and inserts you have to use $db->set().
It returns last inserted id

Just remember to use the clean() function on everything from the user and / or cast the value if you know what the value should be.
