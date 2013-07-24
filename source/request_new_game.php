<?php

/*
	0	Friendship added successfully.
	1	They are already friends.
*/

$player1_name = $_POST['player1_name'];
$player2_name = $_POST['player2_name'];

require_once 'db_functions.php';
$db = new DB_Functions();

	try{
		$result=$db->requestGame($player1_name, $player2_name);
	}
	catch(Exception $e){
		$result=array("code"=>"-1", "message"=>"Unknown problem.");
	}
	
	echo json_encode($result);

?>



