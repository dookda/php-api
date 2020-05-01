<?php
$message = $_REQUEST["message"]; 
$title = $_REQUEST["message"]; //รับค่า

$API_URL = "https://onesignal.com/api/v1/notifications";
$APP_ID = '31d70cb7-1798-4f70-8ed5-eca22cd910e1';    //ดูจาก onesignal  project ->app setting keys & id  ของเรา
$API_KEY = 'ZDVmMDA1NDItOGFjYS00MzA5LThhYmYtMjIxMWEwMGU5YTVl';  //ดูดูจาก onesignal  project ->app setting keys & id  ของเรา
//$message = 'xxxxxxxxx'; // ข้อความที่เราต้องการส่ง

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $API_URL); 
$headers = array(
    'Content-type: application/json',
    'Authorization: Basic '.$API_KEY,
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"app_id\":\"".$APP_ID."\",
\"isIos\": true,
\"isAndroid\":true,
\"included_segments\": [\"All\"],
\"title\": {\"en\":\"".$title."\"},
\"contents\": {\"en\":\"".$message."\"}}");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);
var_dump($response);
?>