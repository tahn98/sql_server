<?php
require_once '../include/DbOperation.php';

$response = array();


if($_SERVER['REQUEST_METHOD']=='POST'){

	if(	isset($_POST['book_id']))
	{
		$db = new DbOperation();
		$response =  $db->getAllChapter($_POST['book_id']);
		
	}
	else{
		$response['error'] = true;
		$response['message'] = "skskskskkspassword";
	}
}
else{
	$response['error'] = true;
	$response['message'] = "Invalid Request";
}
echo json_encode($response);