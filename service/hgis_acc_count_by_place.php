<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn_202.29.52.232_hgis.php');
$dbconn = pg_connect($conn) or die('Could not connect');
$meta = $_GET[meta];

function selectData($type, $code){
	if($type=='all'){
		$sql = "select distinct prov_nam_t, prov_code, count(prov_code) as count_case, prov_nam_t as count_name from ac_data_v group by prov_nam_t, prov_code";		
	}else if($type=='pro'){
        $sql = "select distinct prov_nam_t, prov_code, amp_code, amp_nam_t, count(prov_code) as count_case, amp_nam_t as count_name  from ac_data_v where prov_code='$code' group by prov_nam_t, prov_code, amp_code, amp_nam_t";
		//echo $sql;
	}else if($type=='amp'){
        $sql = "select distinct prov_nam_t, prov_code, amp_code, amp_nam_t, tam_code, tam_nam_t, count(prov_code) as count_case, tam_nam_t as count_name  from ac_data_v where amp_code='$code' group by prov_nam_t, prov_code, amp_code, amp_nam_t, tam_code, tam_nam_t";
        //echo $sql;
    }else if($type=='tam'){
        $sql = "select distinct prov_nam_t, prov_code, amp_code, amp_nam_t, tam_code, tam_nam_t, count(prov_code) as count_case, tam_nam_t as count_name  from ac_data_v where tam_code='$code' group by prov_nam_t, prov_code, amp_code, amp_nam_t, tam_code, tam_nam_t";
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
    if(isset($_GET[type])){
    	selectData($_GET[type], $_GET[code]);
    }else{
    	selectData('all');
    }
};

// Closing connection
pg_close($dbconn);

?>