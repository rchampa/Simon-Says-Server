<?php

/*
	0	Friendship added successfully.
	1	They are already friends.
*/

$game_id = $_POST['game_id'];
$response = $_POST['response'];

require_once 'db_functions.php';
$db = new DB_Functions();

	try{
		$result=$db->responseRequestGame($game_id, $response);
	}
	catch(Exception $e){
		$result=array("code"=>"-1", "message"=>"Unknown problem.");
	}
	
	echo json_encode($result);

?>



