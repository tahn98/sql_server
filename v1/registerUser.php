<?php
require_once '../include/DbOperation.php';

$response = array();


/// chưa sửa
if($_SERVER['REQUEST_METHOD']=='POST'){

	if(	isset($_POST['username']) and isset($_POST['email']) and isset($_POST['password']) )
	{
		$db = new DbOperation();
		$result =  $db->createUser($_POST['username'],$_POST['password'],$_POST['email']);
		if($result == 1){
			$response['error'] = false;
			$response['message'] = "Successful";
		}
		elseif($result == 2){
			$response['error'] = true;
			$response['message'] = "Error Create on Database";
		}
		else{
			$response['error'] = true;
			$response['message'] = "please choice a another email and username";
		}
	}
	else{
		$response['error'] = true;
		$response['message'] = "Missing field";
		$response['l'] = isset($_POST['password']);
	}
}
else{
	$response['error'] = true;
	$response['message'] = "Invalid Request";
}
echo "[";
echo json_encode($response);
echo "]";