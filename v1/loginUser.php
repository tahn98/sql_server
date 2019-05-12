<?php
require_once '../include/DbOperation.php';

$response = array();


/// chưa sửa

if($_SERVER['REQUEST_METHOD']=='POST'){

	if(	isset($_POST['username']) and isset($_POST['password']) )
	{
		$db = new DbOperation();
		$result =  $db->userLogIn($_POST['username'],$_POST['password']);

		if($result == true){
			// $user = $db->getUserByUserName($_POST['username']);
			$response['error'] = false;
			$response['message'] = "Successfully";
			// $response['id'] = $user['id'];
			// $response['email'] = $user['email'];
			// $response['username'] = $user['username'];

		}else{
			$response['error'] = true;
			$response['message'] = "Error";
		}
	}
	else{
		$response['error'] = true;
		$response['message'] = "Invalid username or password";
	}
}
else{
	$response['error'] = true;
	$response['message'] = "Invalid Request";
}
echo "[";
echo json_encode($response);
echo "]";