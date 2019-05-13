<?php
require_once '../include/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='GET'){

		$db = new DbOperation();
		$option = 3;
		$response = $db->getAllBookInfor($option);
}
else{

	$response=[];
}

echo json_encode($response);