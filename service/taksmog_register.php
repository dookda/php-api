<?php

require('../lib/conn_taksmog.php');
$dbconn = pg_connect($conn) or die('Could not connect'); 

pg_query("SET client_encoding = 'utf-8'");

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = file_get_contents('php://input');   
    $dat = json_decode($content, true); 

    $fname = $dat['fname'];
    $uname = $dat['uname'];
    $upass = $dat['upass'];
    $uemail = $dat['uemail'];
    $umobile = $dat['umobile'];
    $utoken = md5($uname).md5($upass).md5($uemail).md5($umobile);

    // selectData($uname, $upass);
    $sql = "insert into tk_user (firstname,uname,upass,uemail,umobile,utoken)values('$fname','$uname','$upass','$uemail','$umobile', '$utoken')";
 
    $result = pg_query($sql);    


    if ($result) {      
      echo json_encode([ 
            ['status' => 'ok','message' => 'ok']
        ]);   
    } else {        
        echo json_encode(
            [['status' => 'error','message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']]
        );    
    } 
}


// Closing connection
pg_close($dbconn);

?>