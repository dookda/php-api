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
    if($type=='amp'){
        $sql = "select distinct tam_nam_t as tam_namt, tam_code as tamcode, amp_nam_t as amp_namt, amp_code as ampcode, prov_nam_t as prov_namt, prov_code as procode from tambon_4326 where amp_code='$code'";      
    }else{
        $sql = "select distinct tam_nam_t as tam_namt, tam_code as tamcode, amp_nam_t as amp_namt, amp_code as ampcode, prov_nam_t as prov_namt, prov_code as procode, 
            st_xmin(st_extent(geom)) as xmin,
            st_ymin(st_extent(geom)) as ymin,
            st_xmax(st_extent(geom)) as xmax,
            st_ymax(st_extent(geom)) as ymax 
            from tambon_4326 
            where tam_code='$code'
            group by tam_nam_t, tam_code, amp_nam_t, amp_code, prov_nam_t, prov_code";
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