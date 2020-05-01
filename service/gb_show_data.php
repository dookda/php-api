<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn.php'); 
$dbconn = pg_connect($conn_garbag) or die('Could not connect'); 
$meta = $_GET[meta];

function selectData(){
    $sql = "select * from gb_mobile"; 

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
	echo 'Service from cgi.uru.ac.th: &copy;2017';
    echo '</br>';
}else{
    //selectData();
    selectData();

};

// Closing connection
pg_close($dbconn);

?>