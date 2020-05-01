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
$app->get('/rain/{UdOrTh}', function($request, $response){ 
    $boundary = $request->getAttribute('UdOrTh');
    
    if($boundary=='ud'){
        $sql = "select sta_id, sta_name, rain_mm, rain_time, sta_source, lat, lon from rain_now_report_ud order by rain_mm desc";
    }elseif($boundary=='th'){
        $sql = "select sta_id, sta_name, rain_mm, rain_time, sta_source, lat, lon from rain_now_report_th order by rain_mm desc";
    }
	
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_rain) or die('Could not connect');  
    
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

$app->get('/rain_stat/{stat}', function($request, $response){ 
    $stat = $request->getAttribute('stat');
    
    $sql = "select $stat(rain_mm) as rain_stat from rain_now_report_ud";
	
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_rain) or die('Could not connect');  
    
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

//get rain week by tam
$app->get('/rainCurrentWeek', function($request, $response){
    // Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_rain) or die('Could not connect');

    $ddate = date("Y-m-d H:i:s");
    $date = new DateTime($ddate);
    $week = "w".$date->format("W");
    //echo "Weeknummer: $week";

    $sql = "select t.tam_code, t.tam_nam_t, t.amp_nam_t, t.prov_nam_t, r.$week as rain
    from rain_now r inner join ln9p_tam t on r.tam_code=t.tam_code 
    where r.$week > 0 order by r.$week desc";

    $rs = pg_query($sql);
    $result = array();

    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;

    // Closing connection
    pg_close($dbconn);
});

// rain community
$app->get('/raincommu/{place}', function($request, $response){  
    // Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_rain) or die('Could not connect');

    $place = $request->getAttribute('place'); 
    if($place=='amp'){
        $sql = "select gid, amp_name from rain_community_station";
    }elseif($place=='tam'){
        $sql = "select gid, tam_name from rain_community_station";
    }elseif ($place=='vill') {
        $sql = "select gid, village from rain_community_station";
    }elseif ($place=='moo') {
        $sql = "select gid, moo from rain_community_station";
    }
    
    $rs = pg_query($sql);
    $result = array();
    while($row = pg_fetch_array($rs)){
      array_push($result, $row);
    }
    $newResponse = $response->withJson($result);
    return $newResponse;
    // Closing connection
    pg_close($dbconn);
});

$app->get('/rform_amp', function($request, $response){  
    // Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_rain) or die('Could not connect');
	
    $sql = "select distinct amp_name from rain_community_station";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
	
	// Closing connection
    pg_close($dbconn);
});

$app->get('/rform_tam/{amp}', function($request, $response){  
    // Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_rain) or die('Could not connect');
	
    $amp = $request->getAttribute('amp');  
    $sql = "select distinct tam_name from rain_community_station where amp_name = '$amp'";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
	
	// Closing connection
    pg_close($dbconn);
});

$app->get('/rform_vill/{tam}', function($request, $response){  
    // Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_rain) or die('Could not connect');
	
    $tam = $request->getAttribute('tam');  
    $sql = "select distinct village from rain_community_station where tam_name = '$tam'";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
	
	// Closing connection
    pg_close($dbconn);
});

$app->get('/rform_moo/{vill}', function($request, $response){  
    // Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_rain) or die('Could not connect');
	
    $vill = $request->getAttribute('vill');  
    $sql = "select distinct station_id, moo from rain_community_station where village = '$vill'";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
	
	// Closing connection
    pg_close($dbconn);
	
});


$app->get('/raintoday_ud', function($request, $response){  
    // Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_rain) or die('Could not connect');
	
    //$tam = $request->getAttribute('tam');  
    $sql = "select * from rain_now_report_ud_tb";
    $rs = pg_query($sql);
    
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
