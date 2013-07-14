<?php
 
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
	
	public function register($name, $password, $gcm_id) {
        
		if( !$this->isUserRegistered($name) ){
			$result = mysql_query("insert into users(name, password, gcm_id, user_created_at) values ('$name', '$password', '$gcm_id', NOW())");
		}
		else{
			return false;
		}
   
		return $result;
    }
	
	public function addFriend($player_name, $friend_name){
	    		 
		if( $this->isUserRegistered($player_name) && $this->isUserRegistered($friend_name) ){
			if ( !$this->areFriends($player_name, $friend_name) ){
				$result = mysql_query("insert into friends(player_name, friend_name, friendship_added_at) values ('$player_name', '$friend_name', NOW())");
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
			
		return $result;
	
	}
	
	public function requestGame($player_name, $friend_name){
	    		 
		if( $this->isUserRegistered($player_name) && $this->isUserRegistered($friend_name) ){
			if ( $this->areFriends($player_name, $friend_name) ){
				$result = mysql_query("insert into games(player1_name, player2_name, friendship_added_at) values ('$player_name', '$friend_name', NOW())");
				return $result;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	public function isGameRegistered($game_id){
		$result = mysql_query("select * from games where game_id='$game_id'");
		if (mysql_num_rows($result) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function acceptGame($game_id){
	
		if($this->isGameRegistered($game_id)){
			$result = mysql_query("update games set in_process=true,accepted=true where game_id='$game_id'");
			return $result;
		}
		else{
			return false;
		}
	
	}
	
	public function isGameInProgress($game_id){
		
		
	}
	
	public function makemove($game_id, $player_name, $move){
	
		if($this->isGameRegistered($game_id)){
			$result = mysql_query("insert into moves (game_id,player_name,move,move_created_at) values ('$game_id', '$player_name', '$move', NOW())");
			return $result;
		}
		else{
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
	
}
 
?>