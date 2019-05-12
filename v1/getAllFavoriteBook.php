<?php
require_once '../include/DbOperation.php';

$response = array();


if($_SERVER['REQUEST_METHOD']=='POST'){

	if(	isset($_POST['user_name']) )
	{
		$db = new DbOperation();
		$response =  $db->GetAllFavoriteBook($_POST['user_name']);
		
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
echo json_encode($response);