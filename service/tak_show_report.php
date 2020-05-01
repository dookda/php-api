<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn.php');
$dbconn = pg_connect($conn_takwildfire) or die('Could not connect');
$meta = $_GET[meta];

function selectData(){
    $sql = "select * from mobile_report order by  pdate desc"; 

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
    selectData();

};

// Closing connection
pg_close($dbconn);

?>