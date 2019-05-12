<?php
require_once '../include/DbOperation.php';

$response = array();


/// chưa sửa
if($_SERVER['REQUEST_METHOD']=='POST'){

	if(	isset($_POST['user_name'])and isset($_POST['book_id']) and isset($_POST['comment_text']) and isset($_POST['comment_date']) )
	{
		$db = new DbOperation();
		$result =  $db->InsertComment($_POST['user_name'],$_POST['book_id'],$_POST['comment_text'],$_POST['comment_date']);
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
			$response['message'] = "Something Went Wrong";
		}
	}
	else{
		$response['error'] = true;
		$response['message'] = "Missing field";
	}
}
else{
	$response['error'] = true;
	$response['message'] = "Invalid Request";
}

echo "[";
echo json_encode($response);
echo "]";