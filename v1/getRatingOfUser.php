<?php
require_once '../include/DbOperation.php';

$response = array();


if($_SERVER['REQUEST_METHOD']=='POST'){

	if(	isset($_POST['user_name']) and isset($_POST['book_id']) )
	{
		$db = new DbOperation();
		$response =  $db->ViewRating($_POST['user_name'],$_POST['book_id']);
		
	}
	else{
		$response['isRating'] = false;
		$response['rate'] = "Invalid username or password";
	}
}
else{
	$response['isRating'] = false;
	$response['rate'] = "Invalid Request";
}
echo "[";
echo json_encode($response);
echo "]";