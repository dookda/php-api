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
    if($type=='pro'){
        $sql = "select distinct amp_nam_t as amp_namt, amp_code as ampcode, prov_nam_t as prov_namt, prov_code as procode from amphoe_4326 where prov_code='$code'";       
    }else{
        $sql = "select distinct amp_nam_t as amp_namt, amp_code as ampcode, prov_nam_t as prov_namt, prov_code as procode, 
            st_xmin(st_extent(geom)) as xmin,
            st_ymin(st_extent(geom)) as ymin,
            st_xmax(st_extent(geom)) as xmax,
            st_ymax(st_extent(geom)) as ymax 
            from amphoe_4326 
            where amp_code='$code'
            group by amp_nam_t, amp_code, prov_nam_t, prov_code";
        //echo $sql;
    }
    //$sql = "select distinct amp_namt, ampcode, prov_namt, procode from c03_district where procode='$prov_code'";
    

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
	echo 'Service from 202.29.52.232: &copy;2017';
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