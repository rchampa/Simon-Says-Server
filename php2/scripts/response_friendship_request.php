<?php

/*
	Response a friendship request
	======== = ========== =======
	700		Friendship completed.
	701		Friendship rejected.
*/

$name = $_POST['name'];
$friend = $_POST['friend'];
$response = $_POST['response'];

require_once 'db_functions.php';
$db = new DB_Functions();

	try{
		$result=$db->responseRequestFriendship($name, $friend, $response);
		
		include_once './GCM.php';
		$gcm = new GCM();
		
		
		switch ($result['code']) {
			case "700": 
			case "701": 
					$user = $db->getUser($friend);
						
					$registation_ids = array($user['gcm_id']);
					
					$message = array("message" => array("code"=>$result['code'],"response"=>$response, "friendName"=>$name));
					$gcm->send_notification($registation_ids, $message);
				
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



