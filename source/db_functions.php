<?php
 
 
 /*
	Codes and messages
	===== === ========
	By default an unknow problem wil be -1.
	
	-1	Unknown problem.
 
	User Registration
	==== ============
	0	User registration successfully.
	1	$user is already registered, cant register the same user twice.
	
	Add a friend
	=== = ======
	2	Friendship added successfully.
	3	They are already friends.
	4	$user is not registered.
	
	Request a new game
	======= = === ====
	5	New game added, waiting for player to accept the request...
	6	There is already a game in progress.
	7	They are not friends.
	
	Response a request game
	======== = ======= ====
	8	Accepted game.
	9	Refused game.
	10	Surrendered $user.
	11	The game is already in progress.
	12	The game does not exists.
	
	Make a move
	==== = ====
	13	New move added.
	14	The game is not ready.
	15	They have another game in progress.
	16	They have another request. They should accept or refuse that request.
	17	Invalid player, the player do not belong to the game.
	
*/
 
class DB_Functions {

    private $db;
 
    //put your code here
    // constructor
    function __construct() {
        include_once './db_connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }
 
    // destructor
    function __destruct() {
    }
	
	public function isUserRegistered($user_name){
		// search if the user is already added in gcm_users
		$result = mysql_query("select * from users where name='$user_name'");
		
		if (mysql_num_rows($result) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function register($name, $password, $gcm_id, $email) {
        
		if( !$this->isUserRegistered($name) ){
			if(mysql_query("insert into users(name, password, email, gcm_id, user_created_at) values ('$name', '$password', '$email', '$gcm_id', NOW())")){
				$result=array("code"=>"0", "message"=>"User registration successfully.");
				return $result;
			}
			else{
				$resultado=array("code"=>"-1", "message"=>"Unknown problem.");
				return $result;
			}
		}
		else{
			$result=array("code"=>"1", "message"=>$name . " is already registered, cant register the same user twice.");
			return $result;
		}
    }
	
	public function addFriend($player_name, $friend_name){
	    		 
		if( !$this->isUserRegistered($player_name) ){
			$result=array("code"=>"4", "message"=>$player_name . "  is not registered.");
			return $result;
		}
		if( !$this->isUserRegistered($friend_name) ){
			$result=array("code"=>"4", "message"=>$friend_name . "  is not registered.");
			return $result;
		}
		
		if ( $this->areFriends($player_name, $friend_name) ){
			$result=array("code"=>"3", "message"=>"They are already friends.");
			return $result;
		}
		
		if(mysql_query("insert into friends(player_name, friend_name, friendship_added_at) values ('$player_name', '$friend_name', NOW())")){
			$result=array("code"=>"2", "message"=>"Friendship added successfully.");
			return $result;
		}
		else{
			$result=array("code"=>"-1", "message"=>"Unknown problem.");
			return $result;
		}
			
	
	}
	
	public function requestGame($player_name, $friend_name){
	
		if( !$this->isUserRegistered($player_name) ){
			$result=array("code"=>"4", "message"=>$player_name . "  is not registered.");
			return $result;
		}
		if( !$this->isUserRegistered($friend_name) ){
			$result=array("code"=>"4", "message"=>$friend_name . "  is not registered.");
			return $result;
		}
		
		if ( !$this->areFriends($player_name, $friend_name) ){
			$result=array("code"=>"7", "message"=>"They are not friends.");
			return $result;
		}
		
		if ($this->areTheyPlaying($player_name, $friend_name) ){
			$result=array("code"=>"15", "message"=>"They another game in progress.");
			return $result;
		}
		
		if ($this->areTheyHaveAnotherRequest($player_name, $friend_name) ){
			$result=array("code"=>"16", "message"=>"They have another request. They should accept or refuse that request.");
			return $result;
		}
		
	    		 
		if(mysql_query("insert into games(player1_name, player2_name, game_created_at) values ('$player_name', '$friend_name', NOW())")){
			$result=array("code"=>"5", "message"=>"New game added, waiting for player to accept the request..");
			return $result;
		}
		
		
		$result=array("code"=>"-1", "message"=>"Unknown problem.");
		return $result;
		
	}
	
	
	public function responseRequestGame($game_id, $response){
	
		if( !$this->isGameRegistered($game_id) ){
			$result=array("code"=>"12", "message"=>"The game does not exists.");
			return $result;
		}
		
		if($this->isGameInProgress($game_id)){
			$result=array("code"=>"11", "message"=>"The game is already in progress.");
			return $result;
		}
		
		switch ($response) {
			case "1":
				
				if(mysql_query("update games set state=2 where game_id='$game_id'")){
					$result=array("code"=>"8", "message"=>"Accepted game.");
					return $result;
				}
				
				break;
				
			case "2": 
			
				if(mysql_query("update games set state=3 where game_id='$game_id'")){
					$result=array("code"=>"9", "message"=>"Refused game.");
					return $result;
				}
				
				break;
			
		}
		
		$result=array("code"=>"-1", "message"=>"Unknown problem.");
		return $result;
		
	}
	
	public function makemove($game_id, $player_name, $move){
	
		if( !$this->isGameRegistered($game_id) ){
			$result=array("code"=>"12", "message"=>"The game does not exists.");
			return $result;
		}
		
		if( !$this->isGameInProgress($game_id)){
			$result=array("code"=>"14", "message"=>"The game is not ready.");
			return $result;
		}
	
		if( !$this->isPlayerOfTheGame($game_id, $player_name)){
			$result=array("code"=>"17", "message"=>"Invalid player, the player do not belong to the game.");
			return $result;
		}
	
		if(mysql_query("insert into moves (game_id,player_name,move,move_created_at) values ('$game_id', '$player_name', '$move', NOW())")){
			$result=array("code"=>"13", "message"=>"New move added.");
			return $result;
		}
		
		$result=array("code"=>"-1", "message"=>"Unknown problem.");
		return $result;
		
	}
	
	public function isGameRegistered($game_id){
		$result = mysql_query("select * from games where game_id='$game_id' and (state!=3 and state!=4)");
		if (mysql_num_rows($result) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isGameInProgress($game_id){
		
		$result = mysql_query("select * from games where game_id='$game_id' and state=2");
		
		if (mysql_num_rows($result) > 0) {
			return true;
		} else {
			return false;
		}
		
	}
	
	
	
    public function getAllUsers() {
        $result = mysql_query("select * from users");
        return $result;
    }
 
	public function getFriends($name) {
        $result = mysql_query("select friend from friends where player_name = '$name'");
        return $result;
    }
	
	public function areFriends($player_name, $friend_name){
		$result = mysql_query("select * from friends where (player_name='$player_name' AND friend_name='$friend_name') OR (player_name='$friend_name' AND friend_name='$player_name')");
		
		if (mysql_num_rows($result) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function areTheyPlaying($player1_name, $player2_name){
		$result = mysql_query("select * from games where state=2 AND ( (player1_name='$player1_name' AND player2_name='$player2_name') OR (player1_name='$player2_name' AND player2_name='$player1_name') )" );
		
		if (mysql_num_rows($result) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function areTheyHaveAnotherRequest($player1_name, $player2_name){
		$result = mysql_query("select * from games where state=1 AND ( (player1_name='$player1_name' AND player2_name='$player2_name') OR (player1_name='$player2_name' AND player2_name='$player1_name') )" );
		
		if (mysql_num_rows($result) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isPlayerOfTheGame($game_id, $player_name){
		$result = mysql_query("select * from games where game_id='$game_id' AND ( player1_name='$player_name' OR player2_name='$player_name' )" );
		
		if (mysql_num_rows($result) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
}
 
?>