<?php

/*
	Add a friend
	=== = ======
	100		Friendship request sent.
	103		They are already friends.
	102		$user is not registered.
*/

$user_name = $_POST['user_name'];
$friend_name = $_POST['friend_name'];

require_once 'db_functions.php';
$db = new DB_Functions();

	try{
		$result=$db->addFriend($user_name, $friend_name);
		include_once './GCM.php';
		$gcm = new GCM();
		
		
		switch ($result['code']) {
			case "100": 
				
				$user = $db->getUser($friend_name);
		
				$registation_ids = array($user['gcm_id']);
				
				$message = array("message" => array("code"=>$result['code'], "user_name"=>$user_name));
				$gcm->send_notification($registation_ids, $message);

				break;
				
			case "101": 
			case "102": 
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



