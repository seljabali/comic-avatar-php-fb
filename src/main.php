<fb:dashboard>
     <fb:action href="main.php">Home</fb:action>
     <fb:action href="stats.php">Statistics</fb:action>
	<fb:action href="create_avatar2.php">Create Avatar</fb:action>
    <fb:action href="invite.php">Invite Friends</fb:action>
</fb:dashboard>


<?
//Facebook initialization
require_once 'appinclude.php';

//Get user-id upon login
$ca_user = $user;
//$ca_user = $facebook->require_login();
$friendsAppUsers = $facebook->api_client->friends_getAppUsers();
$friend = "false";

//Visit applies only to users who have our Comic Avatar app
//<fb:if-user-has-added-app uid = $ca_user>

//Some variables so that changing db, table, and duration will be easier
$db_test = "avatar";
$table = visit;
$duration = 120;
$chance = 1;

//database info
//$db = array('host' => 'localhost:/home/cc/cs160/fa07/class/cs160-at/var/data/cs160-at.sock',
//       'user' => 'root',
//       'pass' => '****'); 

$db = array('host' => 'localhost.fbcomics.com',
		   		'user' => '****',
		  		'pass' => '****');

//connect to the database server
$conn = mysql_connect($db['host'], $db['user'], $db['pass']); 

//report the connection failure or success
if (!$conn) {
   die("there was a problem connecting to the database:".mysql_error());
}

mysql_select_db($db_test, $conn);

//Get user row from db table
$result_user = mysql_query("SELECT * FROM $table WHERE name = $ca_user");
$row_user = mysql_fetch_array($result_user);

//Get a random host row from db table
$result_visit = mysql_query("SELECT * FROM $table ORDER BY RAND()");
$row_visit = mysql_fetch_array($result_visit);

//Set time of beginnning visit and end visit
$time_start = microtime(true);
$time_end = $time_start + $duration;

//Get current user data
$user_data_query = mysql_query("SELECT * FROM user_data WHERE user_id = $user");
$user_data = mysql_fetch_array($user_data_query);
$avatar_pic = $user_data['avatar_pic'];
$avatar_name = $user_data['avatar_name'];
if($row_user['availible'] == 'no'){

}
//Random number generator for occurance of visit
srand(time());
$random = (rand() % $chance);

//Check to see if $row_visit['name'] is a friend of user
foreach($friendsAppUsers as $row_friend)
{
	if ($row_visit['name'] == $row_friend)
	{
		$friend = "true";
	}
}

$user_level_query = mysql_query("SELECT * FROM stats WHERE uid = $user");
$user_level_array = mysql_fetch_array($user_level_query);
$user_level = $user_level_array['level'];

//If there is a visit (first condition), user is available (second condition)
//and the host is available (third condtion) then initialize visiting
if (!$avatar_pic){
$fbml = <<<EndHereDoc
<fb:explanation>  
<fb:message>No avatar found.</fb:message>  
Please use the "Create Avatar" link to create your avatar. 
</fb:explanation>
EndHereDoc;
echo $fbml;
}elseif (($user != 1237057) & ($random == 0) & ($row_user['name'] == $ca_user & $row_user['available'] == yes) 
	& ($row_visit['name'] != $ca_user & $row_visit['available'] == yes) & ($friend == "true"))
{
	//Set db table info so that user and host is unavailable
	mysql_query("UPDATE $table SET available = 'no', sender = $ca_user, receiver = $row_visit[name], time = $time_start WHERE name = $ca_user");
	mysql_query("UPDATE $table SET available = 'no', sender = $ca_user, receiver = $row_visit[name], time = $time_start WHERE name = $row_visit[name]");
$visitor_query = mysql_query("SELECT * FROM $table WHERE name = $ca_user");
$visitor = mysql_fetch_array($visitor_query);
$visitor_id = $visitor['receiver'];
$visitor_data_query = mysql_query("SELECT * FROM user_data WHERE user_id = $visitor_id");
$visitor_data = mysql_fetch_array($visitor_data_query);
$visitor_pic = $visitor_data['avatar_pic'];
$visitor_name = $visitor_data['avatar_name'];
// Get visitors name
$api_key = "****";
$secret = "****";

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
 
$getName = "SELECT name
		FROM user
		WHERE uid = $visitor_id";

$profile = $facebook->api_client->fql_query($getName);

$name = $profile[0]['name'];

	echo "<h1 align = 'center'><font size='5'> Your avatar is away, visiting: </font>" . "<br>";
	echo "<font size='5' face='Verdana'><a href='http://berkeley.facebook.com/profile.php?id=$visitor_id'>$name's " . $visitor_name . "</font></a>";
	echo "<table cellpadding='20'><tr><td><img src='http://www.fbcomics.com/avatar/pics/away.jpg' border=0 height='250' width='250'/></td></tr></table>";
}
elseif (($row_user['name'] == $ca_user & $row_user['available'] == no) &  ($row_user['sender'] == $ca_user))
{
$visitor_query = mysql_query("SELECT * FROM $table WHERE name = $ca_user");
$visitor = mysql_fetch_array($visitor_query);
$visitor_id = $visitor['receiver'];
$visitor_data_query = mysql_query("SELECT * FROM user_data WHERE user_id = $visitor_id");
$visitor_data = mysql_fetch_array($visitor_data_query);
$visitor_pic = $visitor_data['avatar_pic'];
$visitor_name = $visitor_data['avatar_name'];
// Get visitors name
$api_key = "****";
$secret = "****";

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
 
$getName = "SELECT name
		FROM user
		WHERE uid = $visitor_id";

$profile = $facebook->api_client->fql_query($getName);

$name = $profile[0]['name'];

	echo "<h1 align = 'center'><font size='5'> Your avatar is away, visiting: </font>" . "<br>";
	echo "<font size='5' face='Verdana'><a href='http://berkeley.facebook.com/profile.php?id=$visitor_id'>$name's " . $visitor_name . "</font></a>";
	echo "<table cellpadding='20'><tr><td><img src='http://www.fbcomics.com/avatar/pics/away.jpg' border=0 height='250' width='250'/></td></tr></table>";
}
elseif (($row_user['name'] == $ca_user & $row_user['available'] == no) &  ($row_user['receiver'] == $ca_user))
{
$visitor_query = mysql_query("SELECT * FROM $table WHERE name = $ca_user");
$visitor = mysql_fetch_array($visitor_query);
$visitor_id = $visitor['sender'];
$visitor_data_query = mysql_query("SELECT * FROM user_data WHERE user_id = $visitor_id");
$visitor_data = mysql_fetch_array($visitor_data_query);
$visitor_pic = $visitor_data['avatar_pic'];
$visitor_name = $visitor_data['avatar_name'];
$visitor_data_query = mysql_query("SELECT * FROM stats WHERE uid = $visitor_id");
$visitor_data = mysql_fetch_array($visitor_data_query);
$visitor_level = $visitor_data['level'];
// Get visitors name
$api_key = "****";
$secret = "****";

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
 
$getName = "SELECT name
		FROM user
		WHERE uid = $visitor_id";

$profile = $facebook->api_client->fql_query($getName);

$name = $profile[0]['name'];
$your_pic_path = "http://www.fbcomics.com/avatar/pics/level" . $user_level . "/c".$avatar_pic."lvl" . $user_level . ".jpg";
$visitor_pic_path = "http://www.fbcomics.com/avatar/pics/level" . $visitor_level . "/c".$visitor_pic."lvl" . $visitor_level . ".jpg";

$fbml = <<<EndHereDoc
<table cellpadding="30">
<tr>
<td>
<H1 align="center"><font size='5' face='Verdana'>Your:</font><br>
<font size='5' face='Verdana'>$avatar_name</font><br><br>
<img src=$your_pic_path border=0 height="250" width="250"/></H1><br>
<td>
<H1 align="center"><font size='5' face='Verdana'>
<a href="http://berkeley.facebook.com/profile.php?id=$visitor_id">$name</a>'s:</font><br>
<font size='5' face='Verdana'>$visitor_name</font><br><br>
<a href="http://berkeley.facebook.com/profile.php?id=$visitor_id"><img src=$visitor_pic_path border=0 height="250" width="250"/></a></H1><br>
</td>
EndHereDoc;
echo $fbml;
}
else
{
$user_pic_path = "http://www.fbcomics.com/avatar/pics/level". $user_level . "/c".$avatar_pic."lvl" . $user_level. ".jpg";
$fbml = <<<EndHereDoc
<p><font size='5' face='Verdana'>Your Avatar:</font></p>
<H1 align="center"><font size='5' face='Verdana'>$avatar_name</font><br><br>
<img src=$user_pic_path border=0 height="250" width="250"/></H1><br>
EndHereDoc;
echo $fbml;
}

//Order the table by ascending time (default)
$result_end = mysql_query("SELECT * FROM $table ORDER BY time");

//Get current time
$time_current = microtime(true);

//Go through entire table and find expire visits
//Make user and host available again afterwards
while($row_end = mysql_fetch_array($result_end)) 
{
	if($time_current >= ($duration + $row_end['time']))
	{
		mysql_query("UPDATE $table SET available = 'yes', sender = 0, receiver = 0, time = 0 WHERE name = $row_end[name]");
	}
}

//</fb:if-user-has-added-app>

mysql_close($conn);
?>

<!-------------------------
-- PROFILE UPDATE CODE!  --
--------------------------->

<?
	require_once 'appinclude.php';

	// NEW SERVER
	$db = array('host' => 'localhost.fbcomics.com',
		'user' => '****',
		'pass' => '****');

	$api_key = "****";
	$secret = "****";

	$facebook = new Facebook($api_key, $secret);
	$facebook->require_frame();
	$user = $facebook->require_login();

$uid = $_GET["id"];
if (empty($uid))
$uid=$user;

// DATABASE INITIALIZATION
$db = array('host' => 'localhost.fbcomics.com',
	'user' => '****',
	'pass' => '****');

//connect to the database server
$conn = mysql_connect($db['host'], $db['user'], $db['pass']); 

//report the connection failure or success
if (!$conn) {
   die("there was a problem connecting to the database.");
}else{
	mysql_select_db("avatar", $conn) OR DIE ("Unable to select db".mysql_error());
}

// Retrieve image ID
$image_id_query = mysql_query("SELECT * FROM user_data WHERE user_id = $user");
$image_id_array = mysql_fetch_array($image_id_query);
$imageid = $image_id_array['avatar_pic'];
$avatar_name = $image_id_array['avatar_name'];
$image_id_query = mysql_query("SELECT * FROM stats WHERE uid = $user");
$image_id_array = mysql_fetch_array($image_id_query);
$avatar_level = $image_id_array['level'];

$image_path = "http://www.fbcomics.com/avatar/pics/level" . $avatar_level . "/c" . $imageid . "lvl" . $avatar_level . ".jpg";

// Putting Image
$test = "<table cellpadding='10'><tr><td>";




// VISITOR STUFF
$visitor_id_query = mysql_query("SELECT * FROM visit WHERE name = $user");
$visitor_id_array = mysql_fetch_array($visitor_id_query);
if($visitor_id_array && ($visitor_id_array['name'] == $visitor_id_array['sender'])){
$visitor_id = $visitor_id_array['receiver'];
$visitor_data_query = mysql_query("SELECT * FROM user_data WHERE user_id = $visitor_id");
$visitor_data = mysql_fetch_array($visitor_data_query);
$visitor_pic = $visitor_data['avatar_pic'];
$visitor_name = $visitor_data['avatar_name'];

// Get visitors name
$api_key = "****";
$secret = "****";

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
 
$getName = "SELECT name
		FROM user
		WHERE uid = $visitor_id";

$profile = $facebook->api_client->fql_query($getName);

$name = $profile[0]['name'];

$test = $test . "<h1 align = 'center'><font size='5'> Avatar is away, visiting: </font>" . "<br>";
$test = $test . "<font size='5' face='Verdana'><a href='http://berkeley.facebook.com/profile.php?id=$visitor_id'>$name's " . $visitor_name . "<br></font></a>";
$test = $test . "<img src='http://www.fbcomics.com/avatar/pics/away.jpg' border=0 height='250' width='250'/>";
}else{
$test = $test . "<H1 align='center'>";
$test = $test . "<font size='3' face='Verdana'>$avatar_name</font><br><br>";
$test = $test . "<img src = '$image_path' border=0 height='150' width='150'/>" . "<br>";
}

if($visitor_id_array && ($visitor_id_array['name'] == $visitor_id_array['receiver'])){
$visitor_id = $visitor_id_array['sender'];
$visitor_data_query = mysql_query("SELECT * FROM user_data WHERE user_id = $visitor_id");
$visitor_data_array = mysql_fetch_array($visitor_data_query); 
$visitor_imageid = $visitor_data_array['avatar_pic'];
$visitor_avatar_name = $visitor_data_array['avatar_name'];
$visitor_data_query = mysql_query("SELECT * FROM stats WHERE uid = $visitor_id");
$visitor_data_array = mysql_fetch_array($visitor_data_query); 
$visitor_avatar_level = $visitor_data_array['level'];
$visitor_path = "http://www.fbcomics.com/avatar/pics/level".$visitor_avatar_level."/c".$visitor_imageid."lvl".$visitor_avatar_level.".jpg";
// Get visitors name
$api_key = "****";
$secret = "****";

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
 
$getName = "SELECT name
		FROM user
		WHERE uid = $visitor_id";

$profile = $facebook->api_client->fql_query($getName);

$name = $profile[0]['name'];

$test = $test . "<td>" . "<H1 align='center'><font size='3' face='Verdana'><a href='http://berkeley.facebook.com/profile.php?id=$visitor_id'>" . $name . "</a>" . "'s:</font><br>
";
$test = $test . "<font size='3' face='Verdana'>" . $visitor_avatar_name . "</font><br><br>";
$test = $test . "<img src = '$visitor_path' border=0 height='150' width='150'/>";
}else{
$test = $test . "<fb:profile-action url='http://apps.facebook.com/comicavatar/main.php?id='.$uid.''> Go To Avatar Main Page </fb:profile-action>";
}
//echo $test;
$facebook->api_client->profile_setFBML($test, $uid);

?>
