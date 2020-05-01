<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn.php');
$dbconn = pg_connect($conn_rain) or die('Could not connect'); 
$meta = $_GET[meta];

function selectData($acode){
    //$sql = "select * from ud_hp_allyear_sum";
    $sql = "select count(rain_mm) as count_sta, 
            min(rain_mm) as min, 
            max(rain_mm) as max,
            sum(rain_mm) / count(rain_mm) as ave
            from rain_now_report_ud_tb";

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
    echo 'amp_code=6501';
}else{    
    selectData($_GET[amp_code]);       
};

// Closing connection
pg_close($dbconn);

?>