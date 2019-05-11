<?php
require_once '../include/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='GET'){

		$db = new DbOperation();
		$response = $db->getAllBookInfor();
}
else{

	$response=[];
}

echo json_encode($response);