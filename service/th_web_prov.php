<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn_103.40.148.133.php');
$dbconn = pg_connect($conn_hms) or die('Could not connect');
$meta = $_GET[meta];

function selectData($code){
    if($code=='no'){
        $sql = "select prov_nam_t as pv_tn, prov_code as pv_code, st_x(ST_Centroid(geom)) as lon, st_y(ST_Centroid(geom)) as lat from province_4326";       
    }else{
        $sql = "select prov_nam_t as pv_tn, prov_code as pv_code, st_x(ST_Centroid(geom)) as lon, st_y(ST_Centroid(geom)) as lat from province_4326 where prov_code='$code'";
    }   

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
	echo 'Service from 119.59.125.189: &copy;2017';
    echo '</br>';
    echo 'province';
}else{
    //selectData();
    if(isset($_GET[procode])){
    	selectData($_GET[procode]);
    }else{
    	selectData('no');
    }
};

// Closing connection
pg_close($dbconn);

?>