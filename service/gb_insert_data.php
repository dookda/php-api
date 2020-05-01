<?php  
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

require('../lib/conn.php'); 
$dbconn = pg_connect($conn_garbag) or die('Could not connect');   

$postdata = file_get_contents("php://input");
if (isset($postdata)) {
    $dat = json_decode($postdata);
    // $gbdate = $dat->gbdate;

    $fabric = $dat->fabric;
	$food = $dat->food;
	$gbdate = $dat->gbdate;
	$gbdd = $dat->gbdd;
	$gbmmen = $dat->gbmmen;
	$gbmmth = $dat->gbmmth;
	$gbyy = $dat->gbyy;
	$general = $dat->general;
	$glass = $dat->glass;
	$hazard = $dat->hazard;
	$leather = $dat->leather;
	$metal = $dat->metal;
	$mimg = $dat->mimg;
	$optname = $dat->optname;
	$organic = $dat->organic;
	$other = $dat->other;
	$paper = $dat->paper;
	$plastic = $dat->plastic;
	// $pop = $dat->pop;
	$recycle = $dat->recycle;
	$rname = $dat->rname;
	$rock = $dat->rock;
	$tile = $dat->tile;
	$total = $dat->total;
	$wood = $dat->wood;

	$sql = "INSERT INTO gb_mobile(fabric,food,gbdate,gbdd,gbmmen,gbmmth,gbyy,general,glass,hazard,leather,metal,mimg,optname,organic,other,paper,plastic,recycle,rock,tile,total,wood, rname)VALUES($fabric,$food,'$gbdate',$gbdd,'$gbmmen','$gbmmth',$gbyy,$general,$glass,$hazard,$leather,$metal,'$mimg','$optname',$organic,$other,$paper,$plastic,$recycle,$rock,$tile,$total,$wood,$rname)";
	$result = pg_query($sql);

	echo json_encode([
	    'sql' => $sql
	]);
    
}


pg_close($dbconn);

?>