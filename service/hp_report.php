<?php  

header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Origin: *");  
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-alloworigin, access-control-allow-methods, access-control-allow-headers');    
header('Content-Type: text/html; charset=utf-8');

//$link = mysqli_connect('localhost', 'root', '', 'mydb');  
//mysqli_set_charset($link, 'utf8'); 
require('../lib/conn.php'); 
$dbconn = pg_connect($conn_hotspot) or die('Could not connect');   

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get post body content   
    $content = file_get_contents('php://input');   
    // parse JSON   
    $user = json_decode($content, true); 

    $lon = $user['lon']; 
    $lat = $user['lat']; 
    $title = $user['title'];  
    $description = $user['descpt'];  
    $reportor = $user['fname'];  
    $datereport = date("Y-m-d"); 
    $img64 = $user['img64'];  

    //check duplicate $email   
    // $sql2 = "SELECT email FROM userdata WHERE email='$email' ";   
    // $result2 = pg_query($sql2);   
    // $rowcount = pg_num_rows($result2); 

    // if ($rowcount == 1) {    
    //     echo json_encode(
    //         ['status' => 'error','message' => 'ไม่สามารถลงทะเบียนได้ อีเมลนี้มี ผู้ใช้แล้ว']
    //     );    
    //     exit;   
    // } 

    //insert data   
    $sql = "INSERT INTO report (geom,title,description,reportor,datereport,img64) VALUES (ST_GeomFromText('POINT($lon $lat)', 4326),'$title','$description','$reportor','$datereport','$img64');"; 
 
  $result = pg_query($sql);    

  if ($result) {      
      echo json_encode(
            ['status' => 'ok','message' => 'ok นะ']
        );   
    } else {      
        echo json_encode(
            ['status' => 'error','message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']
        );    
    }   
}   
pg_close($dbconn);

?>