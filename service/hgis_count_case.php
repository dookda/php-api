<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn_202.29.52.232_hgis.php');
$dbconn = pg_connect($conn) or die('Could not connect');
$meta = $_GET[meta];

function selectData($x){
	if($x=='no'){
		$sql = "select distinct prov_nam_t as ac_name, count(prov_nam_t) as ac_count from ac_data_v group by prov_nam_t";		
	}else{
		//$sql = "select prov_nam_t, prov_code, st_x(ST_Centroid(geom)) as lon, st_y(ST_Centroid(geom)) as lat from province_4326 where prov_code='$x'";
        $sql = "select distinct amp_nam_t as ac_name, count(amp_nam_t) as ac_count from ac_data_v where prov_code='$x' group by amp_nam_t";
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