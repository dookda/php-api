<?php
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require('../lib/conn.php'); 
$dbconn = pg_connect($conn_garbag) or die('Could not connect');   

	
function chkData($col,$val,$gbdate){    
    if(gettype($val) =='string'){
        $val = "'$val'";
    }

    // $sql = "INSERT INTO gb_mobile ($col) VALUES ($val)";
    // pg_query($sql);


    $rs = pg_query("SELECT gbdate FROM gb_mobile WHERE gbdate = '$gbdate'");
    $result = pg_fetch_array($rs);

    if (empty($result['gbdate'])){  
        $sql = "INSERT INTO gb_mobile ($col) VALUES ($val)";
        pg_query($sql);  
        return $sql;
    }else{
        $sql = "UPDATE gb_mobile SET $col=$val WHERE gbdate = '$gbdate'";
        pg_query($sql);
        return $sql;
    }
}

$postdata = file_get_contents("php://input");
if (isset($postdata)) {
    $request = json_decode($postdata);
    $gbdate = $request->gbdate;

    // echo $gbdate;
        
    foreach($request as $item => $value){
        echo chkData($item,$value,$gbdate);
        echo $item;
        echo $value;
    }
}else {
    echo "Not called properly with username parameter!";
}

// Closing connection
pg_close($dbconn);
?>