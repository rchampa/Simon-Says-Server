<?php

/*
	Add a friend
	=== = ======
	100		Friendship added successfully.
	103		They are already friends.
	102		$user is not registered.
*/

$user_name = $_POST['user_name'];
$friend_name = $_POST['friend_name'];

require_once 'db_functions.php';
$db = new DB_Functions();

	try{
		$result[]=$db->addFriend($user_name, $friend_name);
	}
	catch(Exception $e){
		$result[]=array("code"=>"-1", "message"=>"Unknown problem.");
	}
	
	echo json_encode($result);

?>



