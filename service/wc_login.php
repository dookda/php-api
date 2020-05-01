<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn_wc.php');
$dbconn = pg_connect($conn) or die('Could not connect'); 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = file_get_contents('php://input');   
    $dat = json_decode($content, true); 

    $uname = $dat['uname'];
    $upass = $dat['upass'];

    selectData($uname, $upass);
}


function selectData($uname, $upass){
     $sql = "select firstname as name, utoken as id from wc_user where uname='$uname' and upass='$upass'";      
    createJson($sql);
}

function createJson($sql){
    $res = pg_query($sql);    

    if(pg_num_rows($res)==1){
        $rows = array();

        while($row = pg_fetch_assoc($res)){
            array_push($rows, $row);     
          }
        print json_encode($rows);

    }else{
        print json_encode([
            ['id' => 'error']
        ]);
    }    
}

// Closing connection
pg_close($dbconn);

?>