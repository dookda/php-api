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

$app->get('/fstock', function($request, $response){ 
    // Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_tqf) or die('Could not connect'); 

    //$alrCode = $request->getAttribute('alrCode');  
    $sql = "select * from fstock";
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



$app->post('/signin', function($request, $response){

    $username = $request->getParam('username');
    $password = $request->getParam('password');

    $result = array();

    $newResponse = $response->withJson($result);
    return $newResponse;
    // Closing connection
    pg_close($dbconn);
});


$app->run();
?>
