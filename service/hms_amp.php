<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn.php');
$dbconn = pg_connect($conn_hgis) or die('Could not connect');
$meta = $_GET[meta];

function selectData($type, $code){        
    if($type=='pro'){
        $sql = "select amp_nam_t, amp_code, prov_nam_t, prov_code, st_x(ST_Centroid(geom)) as lon, st_y(ST_Centroid(geom)) as lat from amphoe_4326 where prov_code='$code'";       
    }else{
        $sql = "select amp_nam_t, amp_code, prov_nam_t, prov_code, st_x(ST_Centroid(geom)) as lon, st_y(ST_Centroid(geom)) as lat from amphoe_4326 where amp_code='$code'";
        //echo $sql;
    }  
    createJson($sql);
}

function createJson($sql){
    $res = pg_query($sql);    
    $rows = array();
    while($row = pg_fetch_assoc($res)){
        array_push($rows, $row);     
      }
      print json_encode($rows, JSON_NUMERIC_CHECK);
}

//echo $place.'-'.$code
if($meta=='meta'){
	echo 'Service from 119.59.125.189: &copy;2017';
    echo '</br>';
    echo 'amphoe';
}else{
    if(isset($_GET[procode])){
        selectData('pro',$_GET[procode]);
    }elseif(isset($_GET[ampcode])){
        selectData('amp',$_GET[ampcode]);
    } 
};

// Closing connection
pg_close($dbconn);

?>