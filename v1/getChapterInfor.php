<?php
require_once '../include/DbOperation.php';

$response = array();


if($_SERVER['REQUEST_METHOD']=='POST'){

	if(	isset($_POST['book_id']) and isset($_POST['chapter_id']) )
	{
		$db = new DbOperation();
		$response =  $db->getchapterInfor($_POST['book_id'],$_POST['chapter_id']);
		
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