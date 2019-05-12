<?php
require_once '../include/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){

	if(	isset($_POST['user_name']) and isset($_POST['book_id']))
	{
		$db = new DbOperation();
		$result =  $db->IsFavorite($_POST['user_name'],$_POST['book_id']);
		if($result){
			$response['error'] = false;
			$response['IsFavorite'] = true;
		}
		else{
			$response['error'] = false;
			$response['IsFavorite'] = false;
		}
	}
	else{
		$response['error'] = true;
		$response['IsFavorite'] = "None";
	}
}
else{
	$response['error'] = true;
	$response['IsFavorite'] = "None";
}
echo "[";
echo json_encode($response);
echo "]";