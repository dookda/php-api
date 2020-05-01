<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn.php');
$dbconn = pg_connect($conn_udparcel) or die('Could not connect'); 
$meta = $_GET[meta];

function selectData($val1, $val2){
	if($val2 != ''){
    	$sql = "select cen_x, cen_y, hs_no, building_c, commu_name from building_4326 where hs_no LIKE '%$val1%' and commu_name LIKE '%$val2%'";
	}else{
    	$sql = "select cen_x, cen_y, hs_no, building_c, commu_name from building_4326 where hs_no LIKE '%$val1%'";		
	}
    createJson($sql);
}

function allData(){
    $sql = "select cen_x,cen_y,hs_no,building_c, commu_name from building_4326";
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
	echo 'Service from gisnu.com: &copy;2017';
    echo '</br>';
    echo 'gid=1';
}else{
    if(isset($_GET[val1])){
        selectData($_GET[val1], $_GET[val2]);
    } else {
        allData();
    }   
};

// Closing connection
pg_close($dbconn);

?>