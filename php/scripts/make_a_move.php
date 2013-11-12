<?php

/*
	Make a move
	==== = ====
	400		New move added.
	401		The game is not ready.
	402		The game does not exists.
	403		Invalid player, the player do not belong to the game.
	404		Error 404
*/

$game_id = $_POST['game_id'];
$player_name = $_POST['player_name'];
$move = $_POST['move'];
$guess = $_POST['guess'];

require_once 'db_functions.php';
$db = new DB_Functions();

	try{
		$result=$db->makemove($game_id, $player_name, $move, $guess);
		
		include_once './GCM.php';
		$gcm = new GCM();
		
		
		switch ($result['code']) {
			case "400": 
				if($reg=$db->getGame($game_id)){
					$player1_name = $reg['player1_name'];
					$player2_name = $reg['player2_name'];
					
					if($player1_name==$player_name)
						$user = $db->getUser($player2_name);
					else
						$user = $db->getUser($player1_name);
					
						
					$registation_ids = array($user['gcm_id']);
					
					$message = array("message" => array("code"=>$result['code'], "game_id"=>$game_id, "move"=>$move, "level"=>$reg['level'], "guess"=>$guess));
					$gcm->send_notification($registation_ids, $message);
				}
				break;
				
			case "401": 
			case "402": 
				break;
			default:
		}
		$result = array($result);
	}
	catch(Exception $e){
		$result=array("code"=>"-1", "message"=>"Unknown problem.");
	}
	
	echo json_encode($result);

?>



