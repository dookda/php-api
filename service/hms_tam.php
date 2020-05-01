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
    if($type=='amp'){
        $sql = "select tam_nam_t, tam_code, amp_nam_t, amp_code, prov_nam_t, prov_code, st_x(ST_Centroid(geom)) as lon, st_y(ST_Centroid(geom)) as lat from tambon_4326 where amp_code='$code'";       
    }else{
        $sql = "select tam_nam_t, tam_code, amp_nam_t, amp_code, prov_nam_t, prov_code, st_x(ST_Centroid(geom)) as lon, st_y(ST_Centroid(geom)) as lat from tambon_4326 where tam_code='$code'";
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
      print json_encode($rows);
}

//echo $place.'-'.$code
if($meta=='meta'){
	echo 'Service from 119.59.125.189: &copy;2017';
    echo '</br>';
    echo 'tambon';
}else{
    if(isset($_GET[ampcode])){
        selectData('amp',$_GET[ampcode]);
    }elseif(isset($_GET[tamcode])){
        selectData('tam',$_GET[tamcode]);
    }  
};

// Closing connection
pg_close($dbconn);

?>