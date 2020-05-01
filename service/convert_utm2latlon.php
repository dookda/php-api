<?php
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn.php');
$dbconn = pg_connect($conn_test) or die('Could not connect'); 
$meta = $_GET[meta];

function selectData($e, $n){
    $sql = "with geometry as (SELECT (ST_AsText(ST_Transform(ST_GeomFromText('POINT($e $n)',32647),4326))) as poi) select ST_X(poi) as lon, ST_Y(poi) as lat from geometry;";
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
    echo 'e=616077.686167563&n=1949502.24731857';
}else{
    if(isset($_GET[e]) && isset($_GET[n])){
        selectData($_GET[e], $_GET[n]);
    }
};

// Closing connection
pg_close($dbconn);

?>