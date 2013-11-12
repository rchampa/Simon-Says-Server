<?php

/*
	User Registration
	==== ============
	000	User registration successfully.
	001	$user is already registered, cant register the same user twice.
*/

if (isset($_POST["name"]) && isset($_POST["gcm_id"])) {

	$name = $_POST['name'];
	$gcm_id = $_POST['gcm_id'];
	

	require_once 'db_functions.php';
	
	$db = new DB_Functions();
	

	try{
		$result[] = $db->updateID($name, $gcm_id);	
	}
	catch (Exception $e) {
		$result[] = array("code"=>"-1", "message"=>"Unknown problem.");
	}
	
	echo json_encode($result);	
		
}
else{
	$result[] = array("code"=>"-1", "message"=>"Unknown problem.");
	echo json_encode($result);
}

?>



