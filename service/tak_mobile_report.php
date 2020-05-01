<?php


// $db = pg_connect("host=localhost dbname=udsafe user=postgres password=pgis@CGI@2015") or die("Can't Connect Server");
require('../lib/conn.php');
$dbconn = pg_connect($conn_takwildfire) or die('Could not connect');

pg_query("SET client_encoding = 'utf-8'");

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');



	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		// get post body content
		$content = file_get_contents('php://input');
		// parse JSON
		$user = json_decode($content, true);

		// $id_user = $user['id_user'];
		$lat = $user['lat'];
		$lon = $user['lon'];
		$pname = $user['pname'];
		$pdesc = $user['pdesc'];
		$photo = $user['photo']; 
		$pdate = date("Y-m-d");
		$ptype = $user['ptype'];


		$sql = "INSERT INTO mobile_report (geom, lat, lon, pname, pdesc, photo, pdate, ptype) 
		VALUES (ST_GeomFromText('POINT($lon $lat)',4326), $lat, $lon, '$pname', '$pdesc', '$photo', '$pdate', '$ptype');";

		$result = pg_query($sql);

		echo json_encode(
            ['status' => 'ok','message' => 'ส่งข้อมูลแล้ว']
        );

	}
	// Closing connection
pg_close($dbconn);
?>