<?php  
header('Content-Type: text/html; charset=utf-8');
//header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

require('../lib/conn.php'); 
$dbconn = pg_connect($conn_disaster) or die('Could not connect');   

$postData = file_get_contents("php://input");
$dat = json_decode($postData, true);
$fname = $dat['fname']; 
$alat = $dat['alat']; 
$alon = $dat['alon']; 
$ae = $dat['ae']; 
$an = $dat['an']; 
$az = $dat['az']; 
$hi = $dat['hi']; 
$blat = $dat['blat']; 
$blon = $dat['blon']; 
$bl = $dat['bl']; 
$bah = $dat['bah']; 
$baz = $dat['baz']; 
$clat = $dat['clat']; 
$clon = $dat['clon']; 
$cl = $dat['cl']; 
$cah = $dat['cah']; 
$caz = $dat['caz']; 
$bls = $dat['bls']; 
$bn = $dat['bn']; 
$bz = $dat['bz']; 
$cls = $dat['cls']; 
$ce = $dat['ce']; 
$cn = $dat['cn']; 
$cz = $dat['cz']; 
$hcb = $dat['hcb']; 
$scb = $dat['scb']; 
$slcb = $dat['slcb'];  
$anbc = $dat['anbc']; 

$sql = "INSERT INTO landslide(fname,alat,alon,ae,an,az,hi,blat,blon,bl,bah,baz,clat,clon,cl,cah,caz,bls,bn,bz,cls,ce,cn,cz,hcb,scb,slcb,anbc)VALUES('$fname',$alat,$alon,$ae,$an,$az,$hi,$blat,$blon,$bl,$bah,$baz,$clat,$clon,$cl,$cah,$caz,$bls,$bn,$bz,$cls,$ce,$cn,$cz,$hcb,$scb,$slcb,$anbc)";
$result = pg_query($sql);

echo json_encode([
    'slcb' => $slcb,
    'sql' => $sql
]);
pg_close($dbconn);

?>



