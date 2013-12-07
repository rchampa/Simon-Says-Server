<?php
 
 
 /*
	Codes and messages
	===== === ========
	By default an unknow problem wil be -1.
	
	-1	Unknown problem.
 
	User Registration
	==== ============
	000	User registration successfully.
	001	$user is already registered, please use another username.
	
	Add a friend
	=== = ======
	100		Friendship request sent.
	101		They are already friends.
	102		$user is not registered.
	
	Request a new game
	======= = === ====
	200		New game added, waiting for player to accept the request...
	201		There is already a game in progress.
	202		They are not friends.
	203		$user is not registered.
	204		They have another game in progress.
	205		They have another request. They should accept or refuse that request.	
	
	Response a request game
	======== = ======= ====
	300		Accepted game.
	301		Refused game.
	302		The game is already in progress.
	303		The game does not exists.
	
	Make a move
	==== = ====
	400		New move added.
	401		The game is not ready.
	402		The game does not exists.
	403		Invalid player, the player do not belong to the game.
	404		Error 404
	
	Login
	=====
	500		Login succesfull.
	501		Name or password are wrong.
	
	Login
	=====
	600		Updated complete.
	601		Fail at updating id.
	
	Response a friendship request
	======== = ========== =======
	700		Friendship completed.
	701		Friendship rejected.
	
*/
 
class DB_Functions {

    private $db;
    private $con;
 
    //put your code here
    // constructor
    function __construct() {
        include_once './db_connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->con = $this->db->connect();
    }
 
    // destructor
    function __destruct() {
    }
	
	public function updateID($name, $gcm_id){
		
                $sql = $this->con->prepare('update users set gcm_id=? where name=?');
                $sql->execute(array($name,$gcm_id));
            
		if($sql->rowCount()>0){
			$result=array("code"=>"600", "message"=>"Updated complete.");
			return $result;
		}
		else{
			$result=array("code"=>"601", "message"=>"Fail at updating id.");
			return $result;
		}
		
	}
	
	private function isUserRegistered($user_name){
		// search if the user is already added in gcm_users
                $sql = $this->con->prepare('SELECT name FROM users WHERE name = ?');
                $sql->execute(array($user_name));
                $query = $sql->fetchAll();
            
		if (count($query) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function login($name, $password){
	
                $sql = $this->con->prepare('SELECT name FROM users WHERE name = ? and password=?');
                $sql->execute(array($name,$password));
                $query = $sql->fetchAll();
		
		if (count($query) > 0) {
			$result=array("code"=>"500", "message"=>"Login succesfull.", "gcm_id"=>$reg['gcm_id']);
			return $result;
		} else {
			$result=array("code"=>"501", "message"=>"Name or password are wrong.");
			return $result;
		}
		
	}
	
	public function register($name, $password, $gcm_id, $email) {
            
        
		if( !$this->isUserRegistered($name) ){
                    
                    //$name', '$password', '$email', '$gcm_id'
                    $sql = $this->con->prepare('insert into users(name, password, email, gcm_id, user_created_at) values (?,?,?,?, NOW())');
                    $sql->execute(array($name,$password,$email,$gcm_id));
                    
                    if($sql->rowCount()>0){
                            $result=array("code"=>"000", "message"=>"User registration successfully.");
                            return $result;
                    }
                    else{
                            $result=array("code"=>"-1", "message"=>"Unknown problem.");
                            return $result;
                    }
		}
		else{
			$result=array("code"=>"001", "message"=>$name . " is already registered, please use another username.");
			return $result;
		}
    }
	
    public function addFriend($player_name, $friend_name){

            if( !$this->isUserRegistered($player_name) ){
                    $result=array("code"=>"102", "message"=>$player_name . "  is not registered.");
                    return $result;
            }
            if( !$this->isUserRegistered($friend_name) ){
                    $result=array("code"=>"102", "message"=>$friend_name . "  is not registered.");
                    return $result;
            }

            if ( $this->areFriends($player_name, $friend_name) ){
                    $result=array("code"=>"101", "message"=>"They are already friends.");
                    return $result;
            }

            
            $sql = $this->con->prepare('insert into friends(player_name, friend_name, friendship_added_at) values (?, ?, NOW())');
            $sql->execute(array($player_name,$friend_name));
                    
            //if(mysql_query("insert into friends(player_name, friend_name, friendship_added_at) values ('$player_name', '$friend_name', NOW())")){
            if($sql->rowCount()>0){
                    $result=array("code"=>"100", "message"=>"Friendship request sent.");
                    return $result;
            }
            else{
                    $result=array("code"=>"-1", "message"=>"Unknown problem.");
                    return $result;
            }


    }

    public function requestGame($player_name, $friend_name){

            if( !$this->isUserRegistered($player_name) ){
                    $result=array("code"=>"203", "message"=>$player_name . "  is not registered.");
                    return $result;
            }
            if( !$this->isUserRegistered($friend_name) ){
                    $result=array("code"=>"203", "message"=>$friend_name . "  is not registered.");
                    return $result;
            }

            if ( !$this->areFriends($player_name, $friend_name) ){
                    $result=array("code"=>"202", "message"=>"They are not friends.");
                    return $result;
            }

            if ($this->areTheyPlaying($player_name, $friend_name) ){
                    $result=array("code"=>"204", "message"=>"They another game in progress.");
                    return $result;
            }

            if ($this->areTheyHaveAnotherRequest($player_name, $friend_name) ){
                    $result=array("code"=>"205", "message"=>"They have another request. They should accept or refuse that request.");
                    return $result;
            }


            $sql = $this->con->prepare('insert into games(player1_name, player2_name, game_created_at) values (?, ?, NOW())');
            $sql->execute(array($player_name,$friend_name));
            
            //if(mysql_query("insert into games(player1_name, player2_name, game_created_at) values ('$player_name', '$friend_name', NOW())")){
            if($sql->rowCount()>0){
                    $result=array("code"=>"200", "message"=>"New game added, waiting for player to accept the request..","game_id"=>mysql_insert_id());
                    return $result;
            }


            $result=array("code"=>"-1", "message"=>"Unknown problem.");
            return $result;

    }

    public function responseRequestGame($game_id, $response, $player_name){

            if( !$this->isGameRegistered($game_id) ){
                    $result=array("code"=>"303", "message"=>"The game does not exists.");
                    return $result;
            }

            if($this->isGameInProgress($game_id)){
                    $result=array("code"=>"302", "message"=>"The game is already in progress.");
                    return $result;
            }

            switch ($response) {
                    case "1":
                            $sql = $this->con->prepare('update games set state=2 where game_id=?');
                            $sql->execute(array($game_id));

                            //if(mysql_query("update games set state=2 where game_id='$game_id'")){
                            if($sql->rowCount()>0){
                                    $result=array("code"=>"300", "message"=>"Accepted game.");
                                    return $result;
                            }

                            break;

                    case "2": 
                            $sql = $this->con->prepare('update games set state=4 where game_id=?');
                            $sql->execute(array($game_id));
                            
                            //if(mysql_query("update games set state=4 where game_id='$game_id'")){
                            if($sql->rowCount()>0){
                                    $result=array("code"=>"301", "message"=>"Refused game.");
                                    return $result;
                            }

                            break;

            }

            $result=array("code"=>"-1", "message"=>"Unknown problem.");
            return $result;

    }
	
    public function makemove($game_id, $player_name, $move, $guess){

            if( !$this->isGameRegistered($game_id) ){
                    $result=array("code"=>"402", "message"=>"The game does not exists.");
                    return $result;
            }

            if( !$this->isGameInProgress($game_id)){
                    $result=array("code"=>"401", "message"=>"The game is not ready.");
                    return $result;
            }

            if( !$this->isPlayerOfTheGame($game_id, $player_name)){
                    $result=array("code"=>"403", "message"=>"Invalid player, the player do not belong to the game.");
                    return $result;
            }

            $sql = $this->con->prepare('insert into moves (game_id,player_name,move,move_created_at) values (?, ?, ?, NOW())');
            $sql->execute(array($game_id,$player_name,$move));
            
            //if(mysql_query("insert into moves (game_id,player_name,move,move_created_at) values ('$game_id', '$player_name', '$move', NOW())")){
            if($sql->rowCount()>0){
                    $game = $this->getGame($game_id);

                    if($game['state']=="waiting_player1"){//Comes from waiting for player1

                            if($guess==1){
                                    //XXX Testing.. mysql_query("update games set state=3, score1=score1+1, level=level+1  where game_id='$game_id'");
                                    $sql = $this->con->prepare('update games set state=3, score1=score1+1  where game_id=?');
                                    $sql->execute(array($game_id));
                                    //mysql_query("update games set state=3, score1=score1+1  where game_id='$game_id'");
                            }
                            else{
                                    //XXX Testing.. mysql_query("update games set state=3, level=level+1  where game_id='$game_id'");
                                    $sql = $this->con->prepare('update games set state=3  where game_id=?');
                                    $sql->execute(array($game_id));
                                    //mysql_query("update games set state=3  where game_id='$game_id'");
                            }

                    }
                    elseif($game['state']=="waiting_player2"){//Comes from waiting for player2
                            if($guess==1){
                                    $sql = $this->con->prepare('update games set state=2, score2=score2+1, level=level+1 where game_id=?');
                                    $sql->execute(array($game_id));
                                    //mysql_query("update games set state=2, score2=score2+1, level=level+1 where game_id='$game_id'");
                            }
                            else{
                                    $sql = $this->con->prepare('update games set state=2, level=level+1 where game_id=?');
                                    $sql->execute(array($game_id));
                                    mysql_query("update games set state=2, level=level+1 where game_id='$game_id'");
                            }
                    }

                    $result=array("code"=>"400", "message"=>"New move added.");
                    return $result;
            }

            $result=array("code"=>"-1", "message"=>"Unknown problem.");
            return $result;

    }
	
    public function responseRequestFriendship($name, $friend, $response){

            if($response==1){//Accepted
                    //$result = mysql_query("update friends set state=2 where (player_name='$name' AND friend_name='$friend') OR (player_name='$friend' AND friend_name='$name')");
                    $sql = $this->con->prepare('update friends set state=2 where (player_name=? AND friend_name=?) OR (player_name=? AND friend_name=?)');
                    $sql->execute(array($name,$friend,$friend,$name));
                    if($sql->rowCount()>0){
                        $result=array("code"=>"700", "message"=>"Friendship completed.");
                    }
                    return $result; 
            }
            else{//Rejected
                    //$result = mysql_query("update friends set state=3 where (player_name='$name' AND friend_name='$friend') OR (player_name='$friend' AND friend_name='$name')");
                    $sql = $this->con->prepare('update friends set state=3 where (player_name=? AND friend_name=?) OR (player_name=? AND friend_name=?)');
                    $sql->execute(array($name,$friend,$friend,$name));
                    if($sql->rowCount()>0){
                        $result=array("code"=>"701", "message"=>"Friendship rejected.");
                    }
                    return $result;
            }

            $result=array("code"=>"-1", "message"=>"Unknown problem.");
            return $result;

    }

    private function isGameRegistered($game_id){
            
            // $result = mysql_query("select * from games where game_id='$game_id' and (state!=4 and state!=5)");        
            $sql = $this->con->prepare('select game_id from games where game_id=? and (state!=4 and state!=5)');
            $sql->execute(array($game_id));
            $query = $sql->fetchAll();

            if (count($query) > 0) {
                    return true;
            } else {
                    return false;
            }
    }

    private function isGameInProgress($game_id){

            //$result = mysql_query("select * from games where game_id='$game_id' and (state=2 or state=3)");
            //if (mysql_num_rows($result) > 0) {
                
            $sql = $this->con->prepare('select * from games where game_id=? and (state=2 or state=3)');
            $sql->execute(array($game_id));
            $query = $sql->fetchAll();

            if (count($query) > 0) {    
                    return true;
            } else {
                    return false;
            }

    }

    public function getAllUsers() {
        //$result = mysql_query("select * from users");
        $sql = $this->con->prepare('select * from users');
        $sql->execute();
        return $sql->fetchAll();
    }
	
    public function getUser($name) {
        //$query = mysql_query("select * from users where name = '$name'");
        //return mysql_fetch_array($query);
        $sql = $this->con->prepare('select * from users where name = ?');
        $sql->execute(array($name));
        return $sql->fetch();
        
    }
 
    public function getFriends($name) {
//      $query = mysql_query("select friend from friends where player_name = '$name'");
//      return mysql_fetch_array($query);
        $sql = $this->con->prepare('select friend from friends where player_name = ?');
        $sql->execute(array($name));
        return $sql->fetchAll();
    }
	
    public function getGame($game_id) {
//        $query = mysql_query("select * from games where game_id = '$game_id'");
//        return mysql_fetch_array($query);
        
        $sql = $this->con->prepare('select * from games where game_id = ?');
        $sql->execute(array($game_id));
        return $sql->fetch();
    }
	
    public function areFriends($player_name, $friend_name){
        $sql = $this->con->prepare('select * from friends where (player_name=? AND friend_name=?) OR (player_name=? AND friend_name=?)');
        $sql->execute(array($player_name,$friend_name,$friend_name,$player_name));
        $query = $sql->fetchAll();

        if (count($query) > 0) {  
               return true;
        } else {
                return false;
        }
//        $result = mysql_query("select * from friends where (player_name='$player_name' AND friend_name='$friend_name') OR (player_name='$friend_name' AND friend_name='$player_name')");
//
//        if (mysql_num_rows($result) > 0) {
//                return true;
//        } else {
//                return false;
//        }
    }
	
    public function areTheyPlaying($player1_name, $player2_name){
        
        $sql = $this->con->prepare('select * from games where (state=2 and state=3) AND ( (player1_name=? AND player2_name=?) OR (player1_name=? AND player2_name=?) )');
        $sql->execute(array($player1_name,$player2_name,$player2_name,$player1_name));
        $query = $sql->fetchAll();

        if (count($query) > 0) {  
               return true;
        } else {
                return false;
        }
        
//        $result = mysql_query("select * from games where (state=2 and state=3) AND ( (player1_name='$player1_name' AND player2_name='$player2_name') OR (player1_name='$player2_name' AND player2_name='$player1_name') )" );
//
//        if (mysql_num_rows($result) > 0) {
//                return true;
//        } else {
//                return false;
//        }
    }

    public function areTheyHaveAnotherRequest($player1_name, $player2_name){
        
        $sql = $this->con->prepare('select * from games where state=1 AND ( (player1_name=? AND player2_name=?) OR (player1_name=? AND player2_name=?) )');
        $sql->execute(array($player1_name,$player2_name,$player2_name,$player1_name));
        $query = $sql->fetchAll();

        if (count($query) > 0) {  
               return true;
        } else {
                return false;
        }
        
//        $result = mysql_query("select * from games where state=1 AND ( (player1_name='$player1_name' AND player2_name='$player2_name') OR (player1_name='$player2_name' AND player2_name='$player1_name') )" );
//
//        if (mysql_num_rows($result) > 0) {
//                return true;
//        } else {
//                return false;
//        }
    }

    public function isPlayerOfTheGame($game_id, $player_name){
        
        $sql = $this->con->prepare('select * from games where game_id=? AND ( player1_name=? OR player2_name=? )');
        $sql->execute(array($game_id,$player_name,$player_name));
        $query = $sql->fetchAll();

        if (count($query) > 0) {  
               return true;
        } else {
                return false;
        }
        
//        $result = mysql_query("select * from games where game_id='$game_id' AND ( player1_name='$player_name' OR player2_name='$player_name' )" );
//
//        if (mysql_num_rows($result) > 0) {
//                return true;
//        } else {
//                return false;
//        }
    }
	
}
 
?>