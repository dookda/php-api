<?php  
header('Content-Type: text/html; charset=utf-8');
//header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

require('../lib/conn.php'); 
$dbconn = pg_connect($conn_test) or die('Could not connect');   

$postData = file_get_contents("php://input");
$dat = json_decode($postData, true);
$lon = $dat['lon']; 
$lat = $dat['lat']; 
$title = $dat['title'];  
$descpt = $dat['descpt'];  
$fname = $dat['fname'];
$sname = $dat['sname'];  
$acq_date = date("Y-m-d"); 
$img64 = $dat['img64']; 

$sql = "INSERT INTO road_notify(lon,lat,title,descpt,fname,sname,acq_date,imagepath,geom)VALUES($lon,$lat,'$title','$descpt','$fname','$sname','$acq_date','$img64',ST_GeomFromText('POINT($lon $lat)',4326))";
$result = pg_query($sql);

echo json_encode([
    'img64' => $img64,
    'sql' => $sql
]);
pg_close($dbconn);

?>