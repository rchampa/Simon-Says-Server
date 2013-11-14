<?php

/*
	Request a new game
	======= = === ====
	200		New game added, waiting for player to accept the request...
	201		There is already a game in progress.
	202		They are not friends.
	203		$user is not registered.
	204		They have another game in progress.
	205		They have another request. They should accept or refuse that request.	
*/

$player1_name = $_POST['player1_name'];
$player2_name = $_POST['player2_name'];

require_once 'db_functions.php';
$db = new DB_Functions();

	try{
		$result=$db->requestGame($player1_name, $player2_name);
		
		include_once './GCM.php';
		$gcm = new GCM();
		
		
		switch ($result['code']) {
			case "200": 
				
				$user = $db->getUser($player2_name);
		
				$registation_ids = array($user['gcm_id']);
				
				$message = array("message" => array("code"=>$result['code'], "game_id"=>$result['game_id'], "opponent_name"=>$player1_name));
				$gcm->send_notification($registation_ids, $message);

				break;
				
			case "201": 
			case "202": 
				break;
			default:
		}
		
		$result = array($result);
	}
	catch(Exception $e){
		$result[]=array("code"=>"-1", "message"=>"Unknown problem.");
	}
	
	echo json_encode($result);

?>



