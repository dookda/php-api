<?php
// echo "OK";

require 'vendor/autoload.php';

//$app = new \Slim\App(["settings" => $config]);
$app = new \Slim\App(["settings"]);

// CorsSlim for Cross domain request
/* 
$corsOptions = array(
    "origin" => "*",
    "exposeHeaders" => array("Content-Type", "X-Requested-With", "X-authentication", "X-client"),
    "allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
);
$cors = new \CorsSlim\CorsSlim($corsOptions);
$app->add($cors); 
*/


$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, X-authentication, X-client, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->get('/users/{email}', function($request, $response){  
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    $email = $request->getAttribute('email');  
    $sql = "select count(user_email) from users where user_email='$email'";
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

$app->get('/gbpoint', function($request, $response){  
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    //$alrCode = $request->getAttribute('alrCode');  
    $sql = "select * from gb_point";
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

$app->get('/apt_location', function($request, $response){  
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    //$alrCode = $request->getAttribute('alrCode');  
    $sql = "select * from gb_localadmin";
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


$app->get('/stat_month', function($request, $response){  
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    //$alrCode = $request->getAttribute('alrCode');  
    $sql = "select * from gb_data";
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

$app->get('/prov', function($request, $response){   
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    //$alrCode = $request->getAttribute('alrCode');  
    $sql = "select distinct prov_code, prov_name from village";
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

$app->get('/amp/{pcode}', function($request, $response){   
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    $pcode = $request->getAttribute('pcode');  
    $sql = "select distinct amp_code, amp_name, prov_code from village where prov_code = '$pcode'";
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

$app->get('/tam/{acode}', function($request, $response){   
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    $acode = $request->getAttribute('acode');  
    $sql = "select distinct tam_code, tam_name, amp_code from village where amp_code = '$acode'";
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

$app->get('/vill/{tcode}', function($request, $response){   
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    $tcode = $request->getAttribute('tcode');  
    $sql = "select distinct vill_code, vill_name, tam_code from village where tam_code = '$tcode'";
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

// query for echart
$app->get('/stat/{place}/{code}', function($request, $response){ 
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    $place = $request->getAttribute('place'); 
    $code = $request->getAttribute('code'); 
    
    if($place=='province'){
        $sql = "select * from pro_dhf where prov_code = '$code'";
    }elseif($place=='amphoe'){
        $sql = "select * from amp_dhf where amp_code = '$code'";
    }elseif ($place=='tambon') {
        $sql = "select * from tam_dhf where tam_code = '$code'";
    }elseif ($place=='village') {
        $sql = "select * from village where vill_code = '$code'";
    }
    
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

$app->get('/stat_prov/{pcode}', function($request, $response){   
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    $pcode = $request->getAttribute('pcode');  
    $sql = "select * from pro_dhf where prov_code = '$pcode'";
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

$app->get('/stat_amp/{acode}', function($request, $response){   
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    $acode = $request->getAttribute('acode');  
    $sql = "select * from amp_dhf where amp_code = '$acode'";
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

$app->get('/stat_tam/{tcode}', function($request, $response){   
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    $tcode = $request->getAttribute('tcode');  
    $sql = "select * from tam_dhf where tam_code = '$tcode'";
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

$app->get('/stat_vill/{vcode}', function($request, $response){   
	// Connect database
    require('../lib/conn.php');
    $dbconn = pg_connect($conn_gb) or die('Could not connect');
	
    $vcode = $request->getAttribute('vcode');  
    $sql = "select * from village where vill_code = '$vcode'";
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

$app->run();
 ?>
