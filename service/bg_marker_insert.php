<?php

header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require('../lib/conn.php');
$dbconn = pg_connect($conn_test) or die('Could not connect');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get post body content   
    $postdata = file_get_contents('php://input');   
    // parse JSON   
    $content = json_decode($postdata, true); 

    // $_desc = $content['_desc'];
    $lon = $content['lon']; 
    $lat = $content['lat']; 
    // $geom = $content['geom'];
 
    $sql = "INSERT INTO bg_location(geom, lon, lat) VALUES (ST_GeomFromText('POINT($lon $lat)', 4326),$lat, $lon)"; 
 
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