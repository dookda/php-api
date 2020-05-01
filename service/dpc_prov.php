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
		$sql = "select distinct prov_nam_t as prov_namt, prov_code as procode from province_4326";		
	}else{
		$sql = "select distinct prov_nam_t as prov_namt, prov_code as procode, st_xmin(st_extent(geom)) as xmin,
				st_ymin(st_extent(geom)) as ymin,
				st_xmax(st_extent(geom)) as xmax,
				st_ymax(st_extent(geom)) as ymax 
				from province_4326 where prov_code='$x' group by prov_nam_t, prov_code";
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
	echo 'Service from 202.29.52.232: &copy;2017';
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