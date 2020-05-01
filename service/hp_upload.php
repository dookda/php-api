<?php
	if ($_FILES["file"]["error"] > 0){
		echo "Error Code: " . $_FILES["file"]["error"] . "<br />";
		echo json_encode(
            ['status' => 'ok','message' => 'เกิดข้อผิดพลาดระหว่างส่งข้อมูล']
          );
	}else{
		if (file_exists("/files/".$_FILES["file"]["name"])){
		  echo $_FILES["file"]["name"];
		}else{
		  move_uploaded_file($_FILES["file"]["tmp_name"],"uploads/".$_FILES["file"]["name"]);		  
		  echo json_encode(
            ['status' => 'ok','message' => 'ส่งข้อมูลแล้ว']
          );

		}
	}
?>