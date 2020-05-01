<?php

header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require('../lib/conn_202.29.52.232_hgis.php');
$dbconn = pg_connect($conn) or die('Could not connect');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get post body content   
    $postdata = file_get_contents('php://input');   
    // parse JSON   
    $content = json_decode($postdata, true); 

    $lon = $content['lon'];
    $lat = $content['lat'];
    $acc_date = $content['acc_date'];
    $acc_time = $content['acc_time'];
    $acc_time_hr = $content['acc_time_hr'];
    $acc_caption = $content['acc_caption'];
    $acc_desc = $content['acc_desc'];
    $acc_img = $content['acc_img'];
    $acc_link = $content['acc_link'];



 
    $sql = "INSERT INTO deadsongkran2018_4326(geom, lon, lat, acc_date, acc_time, acc_time_hr, acc_caption, acc_desc, acc_img, acc_link, acc_upload) VALUES ( ST_GeomFromText('POINT($lon $lat)',4326), $lon, $lat, '$acc_date', '$acc_time', '$acc_time_hr', '$acc_caption', '$acc_desc', '$acc_img', '$acc_link', current_timestamp())"; 
 
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