<?php
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

//pg_connect("host=localhost user=postgres password=1234 dbname=hgis") or die("Can't Connect Server");
require('../lib/conn_202.29.52.232_hgis.php');
$dbconn = pg_connect($conn) or die('Could not connect');

function selectData($a,$b){

  $sql_all = "SELECT json_build_object(
    'type',     'FeatureCollection',
    'features', json_agg(feature)
)
FROM (
  SELECT json_build_object(
    'type',       'Feature',
    'id',         gid,
    'geometry',   ST_AsGeoJSON(geom)::json,
    'properties', to_json(row)
  ) AS feature
  FROM (SELECT * FROM ac_data_v) row) features;";

$sql_pro = "SELECT json_build_object(
    'type',     'FeatureCollection',
    'features', json_agg(feature)
)
FROM (
  SELECT json_build_object(
    'type',       'Feature',
    'id',         gid,
    'geometry',   ST_AsGeoJSON(geom)::json,
    'properties', to_json(row)
  ) AS feature
  FROM (SELECT * FROM ac_data_v where prov_code='$b') row) features;";

$sql_amp = "SELECT json_build_object(
    'type',     'FeatureCollection',
    'features', json_agg(feature)
)
FROM (
  SELECT json_build_object(
    'type',       'Feature',
    'id',         gid,
    'geometry',   ST_AsGeoJSON(geom)::json,
    'properties', to_json(row)
  ) AS feature
  FROM (SELECT * FROM ac_data_v where amp_code='$b') row) features;";

$sql_tam = "SELECT json_build_object(
    'type',     'FeatureCollection',
    'features', json_agg(feature)
)
FROM (
  SELECT json_build_object(
    'type',       'Feature',
    'id',         gid,
    'geometry',   ST_AsGeoJSON(geom)::json,
    'properties', to_json(row)
  ) AS feature
  FROM (SELECT * FROM ac_data_v where tam_code='$b') row) features;";

  if($a=='all'){
    $sql=$sql_all;
  }elseif($a=='pro'){
    $sql=$sql_pro;
  }elseif($a=='amp'){
    $sql=$sql_amp;
  }elseif($a=='tam'){
    $sql=$sql_tam;
  }

  //echo($sql);

  $res = pg_query($sql);
  
  while ($row = pg_fetch_row($res)) {
    echo $row[0];
  }

}

//echo $place.'-'.$code
if($meta=='meta'){
  echo 'Service from 119.59.125.189: &copy;2017';
    echo '</br>';
    echo 'amphoe';
}else{
    if(isset($_GET[procode])){
        selectData('pro',$_GET[procode]);
    }elseif(isset($_GET[ampcode])){
        selectData('amp',$_GET[ampcode]);
    }elseif(isset($_GET[tamcode])){
        selectData('tam',$_GET[tamcode]);
    }else{
        selectData('all','all');
    } 
};
// Closing connection
pg_close($dbconn);
?>