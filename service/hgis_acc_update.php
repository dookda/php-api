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

    // $ac_desc = $content['ac_desc'];
    $gid = $content['gid']; 
    // $lat = $content['lat']; 
    $geom = $content['geom'];
 
    // $sql = "INSERT INTO ac_add_point(ac_desc,geom) VALUES ( '$ac_desc', ST_SetSRID(st_geomfromgeojson('$geom'), 4326))"; 
    $sql = "UPDATE ac_add_point SET geom = ST_SetSRID(st_geomfromgeojson('$geom'), 4326) WHERE gid = '$gid'";
 
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