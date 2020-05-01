<?php  

header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Origin: *");  
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-alloworigin, access-control-allow-methods, access-control-allow-headers');    

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
 
require('../lib/conn.php'); 
$dbconn = pg_connect($conn_test) or die('Could not connect');   

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get post body content   
    $content = file_get_contents('php://input');   
    // parse JSON   
    $user = json_decode($content, true); 

    $fullname = $user['fullname'];   
    $email = $user['email'];   
    $password = $user['password']; 

    //check duplicate $email   
    $sql2 = "SELECT email FROM user WHERE email='$email' ";   
    $result2 = pg_query($link, $sql2);   
    $rowcount = pg_num_rows($result2); 

    if ($rowcount == 1) {    
        echo json_encode(
            ['status' => 'error','message' => 'ไม่สามารถลงทะเบยีนได้ อเีมลนี้มี ผู้ใช้แล้ว']
        );    
        exit;   
    } 

    //insert data   
    $sql = "INSERT INTO user (fullname, email, pwd) VALUES ('$fullname', '$email', '$password');"; 
 
  $result = pg_query($link, $sql);      
  if ($result) {      
      echo json_encode(
          ['status' => 'ok','message' => 'บันทกึขอ้มูลเรียบร้อยนะ']
        );   
    } else {      
        echo json_encode(
            ['status' => 'error','message' => 'เกิดขอ้ผิดพลาดในการบันทึกขอมูล']
        );    
    }   
}   
pg_close($dbconn);

?>