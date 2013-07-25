<?php

/*
	Login
	=====
	600		Updated complete.
	601		Fail at updating id.
*/

if (isset($_POST["name"]) && isset($_POST["password"]) ) {

	$name = $_POST['name'];
	$password = $_POST['password'];

	require_once 'db_functions.php';
	
	$db = new DB_Functions();
	

	try{
		$result = $db->login($name, $password);	
	}
	catch (Exception $e) {
		$result = array("code"=>"-1", "message"=>"Unknown problem.");
	}
	echo json_encode($result);	
		
}
else{
	$result[] = array("code"=>"-1", "message"=>"Unknown problem.");
	echo json_encode($result);
}

?>



