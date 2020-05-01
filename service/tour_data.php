<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn_202.29.52.232_tour.php');
$dbconn = pg_connect($conn) or die('Could not connect');  
$meta = $_GET[meta];

function selectData(){
	$sql = "select gid,t_name,t_type,t_identity,t_potent,t_address,t_prov,lat,lon,t_ac,t_in,t_cc,t_sq,t_la,t_re,t_etc from tour_4326";	  
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
	echo 'Service from 202.29.52.232: &copy;2017';
    echo '</br>';
    echo 'province';
}else{
	selectData();
    //selectData();
    // if(isset($_GET[procode])){
    // 	selectData($_GET[procode]);
    // }else{
    // 	selectData('no');
    // }
};

// Closing connection
pg_close($dbconn);

?>