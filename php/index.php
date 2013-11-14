<!DOCTYPE html>

<?

header('Content-Type: text/html; charset=utf-8');

$host = $_SERVER['HTTP_HOST'];

setlocale(LC_TIME, "es_ES.utf8");

date_default_timezone_set('Europe/Madrid');

?>

<html>
    <head>
        
    </head>
    <body>
		<ul>
			<li><a href="ui/login.html">Login</a></li>
			<li><a href="ui/updateGCM.html">Update GCM</a></li>
			<li><a href="ui/adduser.html">Add new user</a></li>
			<li><a href="ui/add_friend.html">Add a new friend</a></li>
			<li><a href="ui/response_friendship_request.html">Response a friendship request</a></li>
			<li><a href="ui/request_new_game.html">Request for a new game</a></li>
			<li><a href="ui/response_request_game.html">Response a request game</a></li>
			<li><a href="ui/make_a_move.html">Make a move</a></li>
		</ul>
    </body>
</html>