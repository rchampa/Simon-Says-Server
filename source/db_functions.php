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
	
	public function addContact($user_name, $name_user_contact){
	             
		$result = mysql_query("insert into contactos(usuario, contacto, added_at) values ('$user_name', '$name_user_contact', NOW())");
			
		return $result;
	
	}
	
	
 
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $password, $gcm_id) {
        // insert user into database
        $result = mysql_query("insert into users(name, password, gcm_id, created_at) VALUES('$name', '$password', '$gcm_id', NOW())");
        
		return $result;
    }
 
    /**
     * Getting all users
     */
    public function getAllUsers() {
        $result = mysql_query("select * from users");
        return $result;
    }
 
	public function getFriends($name) {
        $result = mysql_query("select friend from friends where player_name = '$name'");
        return $result;
    }
	
}
 
?>