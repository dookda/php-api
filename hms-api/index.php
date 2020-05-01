<?php
// echo "OK";

require 'vendor/autoload.php';
include "../lib/hms.php";
conndb();


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


$app->get('/parcel/{alrCode}', function($request, $response){  
    $alrCode = $request->getAttribute('alrCode');  
    $sql = "select * from alr_parcel where alrcode = '$alrCode'";
    $rs = pg_query($sql);
    
    $result = array();

    while($row = pg_fetch_array($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
});

$app->get('/denguepoint', function($request, $response){  
    //$alrCode = $request->getAttribute('alrCode');  
    $sql = "select * from v_dengue_point";
    $rs = pg_query($sql);
    
    $result = array();

    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
});

$app->get('/prov', function($request, $response){  
    //$alrCode = $request->getAttribute('alrCode');  
    $sql = "select distinct prov_code, prov_name from vill_dhf";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
});

$app->get('/amp/{pcode}', function($request, $response){  
    $pcode = $request->getAttribute('pcode');  
    $sql = "select distinct amp_code, amp_name, prov_code from vill_dhf where prov_code = '$pcode'";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
});

$app->get('/tam/{acode}', function($request, $response){  
    $acode = $request->getAttribute('acode');  
    $sql = "select distinct tam_code, tam_name, amp_code from vill_dhf where amp_code = '$acode'";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
});

$app->get('/vill/{tcode}', function($request, $response){  
    $tcode = $request->getAttribute('tcode');  
    $sql = "select distinct vill_code, vill_name, tam_code from vill_dhf where tam_code = '$tcode'";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
});

// query for echart
$app->get('/stat/{place}/{code}', function($request, $response){
    $place = $request->getAttribute('place'); 
    $code = $request->getAttribute('code'); 
    
    if($place=='province'){
        $sql = "select * from pro_dhf where prov_code = '$code'";
    }elseif($place=='amphoe'){
        $sql = "select * from amp_dhf where amp_code = '$code'";
    }elseif ($place=='tambon') {
        $sql = "select * from tam_dhf where tam_code = '$code'";
    }elseif ($place=='village') {
        $sql = "select * from vill_dhf where vill_code = '$code'";
    }
    
    $rs = pg_query($sql);
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }
    $newResponse = $response->withJson($result);
    return $newResponse;
});

$app->get('/stat_prov/{pcode}', function($request, $response){  
    $pcode = $request->getAttribute('pcode');  
    $sql = "select * from pro_dhf where prov_code = '$pcode'";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
});

$app->get('/stat_amp/{acode}', function($request, $response){  
    $acode = $request->getAttribute('acode');  
    $sql = "select * from amp_dhf where amp_code = '$acode'";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
});

$app->get('/stat_tam/{tcode}', function($request, $response){  
    $tcode = $request->getAttribute('tcode');  
    $sql = "select * from tam_dhf where tam_code = '$tcode'";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
});

$app->get('/stat_vill/{vcode}', function($request, $response){  
    $vcode = $request->getAttribute('vcode');  
    $sql = "select * from vill_dhf where vill_code = '$vcode'";
    $rs = pg_query($sql);
    
    $result = array();
    while($row = pg_fetch_assoc($rs)){
      array_push($result, $row);
    }

    $newResponse = $response->withJson($result);
    return $newResponse;
});


// mai roooooo
$app->post('/signin', function($request, $response){

	$username = $request->getParam('username');
  $password = $request->getParam('password');

  $result = array();

  $newResponse = $response->withJson($result);
  return $newResponse;

});



$app->run();
closedb();
 ?>
