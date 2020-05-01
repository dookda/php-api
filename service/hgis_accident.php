<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn_202.29.52.232_hgis.php');
$dbconn = pg_connect($conn) or die('Could not connect');
$meta = $_GET[meta];

function selectData(){
    $sql = "select lon,lat,ac_date,ac_time,ac_dt,ac_desc,ac_img from ac_data";
    //s$sql = "select lon,lat from ac_data";
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
    echo 'prov_code=65';
}else{
    selectData();   
};

// Closing connection
pg_close($dbconn);

?>