<?php

/*
	300		Accepted game.
	301		Refused game.
	302		The game is already in progress.
	303		The game does not exists.
*/

$game_id = $_POST['game_id'];
$response = $_POST['response'];
$player_name = $_POST['player_name'];

require_once 'db_functions.php';
$db = new DB_Functions();

	try{
		$result=$db->responseRequestGame($game_id, $response, $player_name);
		
		include_once './GCM.php';
		$gcm = new GCM();
		
		
		switch ($result['code']) {
			case "300": 
			case "301": 
				if($reg=$db->getGame($game_id)){
					$player1_name = $reg['player1_name'];
					$player2_name = $reg['player2_name'];
					
					if($player1_name==$player_name)
						$user = $db->getUser($player2_name);
					else
						$user = $db->getUser($player1_name);
					
						
					$registation_ids = array($user['gcm_id']);
					
					$message = array("message" => array("code"=>$result['code'],"game_id"=>$game_id));
					$gcm->send_notification($registation_ids, $message);
				}
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



