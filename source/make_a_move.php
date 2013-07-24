<?php

/*
	0	Friendship added successfully.
	1	They are already friends.
*/

$game_id = $_POST['game_id'];
$player_name = $_POST['player_name'];
$move = $_POST['move'];

require_once 'db_functions.php';
$db = new DB_Functions();

	try{
		$result=$db->makemove($game_id, $player_name, $move);
	}
	catch(Exception $e){
		$result=array("code"=>"-1", "message"=>"Unknown problem.");
	}
	
	echo json_encode($result);

?>



