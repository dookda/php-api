<?php
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn.php');
$dbconn = pg_connect($conn_test) or die('Could not connect'); 
$meta = $_GET[meta];

function selectData($lat, $lon){
    $sql = "with geometry as (SELECT (ST_AsText(ST_Transform(ST_GeomFromText('POINT($lon $lat)',4326),32647))) as poi) select ST_X(poi) as e, ST_Y(poi) as n from geometry;";
    createJson($sql);
}

function createJson($sql){
    $res = pg_query($sql);    
    $rows = array();
    while($row = pg_fetch_assoc($res)){
        array_push($rows, $row);     
      }
      print json_encode($rows);
}

//echo $place.'-'.$code
if($meta=='meta'){
    echo 'Service from gisnu.com: &copy;2017';
    echo '</br>';
    echo 'lat=100.094175&lon=17.629237';
}else{
    if(isset($_GET[lat]) && isset($_GET[lon])){
        selectData($_GET[lat], $_GET[lon]);
    }
};

// Closing connection
pg_close($dbconn);

?>