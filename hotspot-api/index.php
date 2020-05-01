<?php
// echo "OK";
require 'vendor/autoload.php';


//$app = new \Slim\App(["settings" => $config]);
$app = new \Slim\App(["settings"]);

// CorsSlim for Cross domain request
$corsOptions = array(
    "origin" => "*",
    "exposeHeaders" => array("Content-Type", "X-Requested-With", "X-authentication", "X-client"),
    "allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
);
$cors = new \CorsSlim\CorsSlim($corsOptions);
$app->add($cors);

//// select by location
$app->get('/hotspot/{UdOrTh}', function($request, $response){ 
    $boundary = $request->getAttribute('UdOrTh');
    
    if($boundary=='ud'){
        $sql = "select acq_date, confidence, b1, b3, frp, satellite from hotspot_ud_today order by acq_date desc";
    }elseif($boundary=='th'){
        $sql = "select acq_date, confidence, b1, b3, frp, satellite from hotspot_th order by acq_date desc";
    }
	
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_hp) or die('Could not connect');  
    
    $rs = pg_query($dbconn, $sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;

    // Closing connection
    pg_close($dbconn);
});

$app->run();
?>
