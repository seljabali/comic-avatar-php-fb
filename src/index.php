
<?
function connectToDB(){
	$db = array('host' => 'localhost.fbcomics.com',
		   		'user' => '****',
		  		'pass' => '****');
	
	$conn = mysql_connect($db['host'], $db['user'], $db['pass']); 
	if (!$conn)
		die("There was a problem connecting to the database.");
	else
		mysql_select_db("avatar", $conn);
		
	return $conn;
}
function isCompleted($element){
	if ($element == NULL or $element == "")
		return 0;
	else
		return 1;
}

require_once 'appinclude.php';
echo "<a href='http://apps.facebook.com/comicavatar/main.php'><img src='http://www.fbcomics.com/avatar/pics/party.jpg' border=0 height='500' width='690'/>";
$fbml = <<<EndHereDoc
<fb:editor action="http://apps.facebook.com/comicavatar/main.php" labelwidth="300">
<fb:editor-buttonset>
<fb:editor-button value="Next!"/>
</fb:editor-buttonset>
</fb:editor>
EndHereDoc;
echo $fbml;
//echo "Loading...";

$api_key = "****";
$secret = "****";

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
$user = $facebook->require_login();

$connection = connectToDB();

if (!$connection)
	echo "Couldn't Connect to the DB";
	
$result = mysql_query("SELECT uid, wallposts,notes,profile,status,points,level 
	   					FROM stats 
	   					WHERE uid = $user");
$row = mysql_fetch_array($result);

//echo "Result: $result<br />Row: $row";
if ($row == NULL){	
	//echo "Adding You!<br />";
	$getProfile = "SELECT uid, first_name, last_name, name, pic, affiliations, profile_update_time, religion, birthday, sex, hometown_location, meeting_sex, meeting_for, relationship_status, political, current_location, activities, interests, is_app_user, music, tv, movies, books, quotes, about_me, hs_info, education_history, work_history, notes_count, wall_count, status, has_added_app
			FROM user
			WHERE uid = $user";	
	$getFriends = "SELECT uid2 FROM friend WHERE uid1=$user";
	

	//Executing Them
	$profile = $facebook->api_client->fql_query($getProfile);
	$friendCount = $facebook->api_client->fql_query($getFriends);
	//$groupCount = $facebook->api_client->fql_query($getGroups);


	//Updated USER INFO
	$newWall = $profile[0]['wall_count'];
	$newNotes = $profile[0]['notes_count'];
	$newProfile = $profile[0]['profile_update_time'];
	$newStatus = $profile[0]['status']['time'];
	$newFriendCount = sizeof($friendCount);
	$newProfileCompletion = 0;
	$newProfileCompletion = isCompleted($profile[0]['affiliations'])+ isCompleted($profile[0]['about_me'])+
							isCompleted($profile[0]['activities'])+	isCompleted($profile[0]['birthday'])+
							isCompleted($profile[0]['books'])+ isCompleted($profile[0]['current_location']['city'])+
							isCompleted($profile[0]['current_location']['state'])+ isCompleted($profile[0]['current_location']['country'])+
							isCompleted($profile[0]['education_history']['year'])+ isCompleted($profile[0]['education_history']['name'])+
							isCompleted($profile[0]['education_history']['concentrations'])+isCompleted($profile[0]['hometown_location']['city'])+
							isCompleted($profile[0]['hometown_location']['state'])+ isCompleted($profile[0]['hometown_location']['country'])+
							isCompleted($profile[0]['hs_info']['hs1_name'])+ isCompleted($profile[0]['hs_info']['grad_year'])+
							isCompleted($profile[0]['interests'])+ isCompleted($profile[0]['meeting_for'])+
							isCompleted($profile[0]['meeting_sex'])+isCompleted($profile[0]['movies'])+
							isCompleted($profile[0]['music'])+isCompleted($profile[0]['quotes'])+
							isCompleted($profile[0]['religion'])+isCompleted($profile[0]['sex'])+
							isCompleted($profile[0]['tv']);

	$newProfileCompletion = $newProfileCompletion * 4;
	

	//echo "WallCount = $newWall, NoteCount = $newNotes, Profile = $newProfile, Status = $newStatus, Complete = $newProfileCompletion, Friends = $newFriendCount	";
	mysql_query("INSERT INTO stats (uid, wallposts, notes, profile, status, points, level, complete, friends) VALUES
								   ($user, $newWall, $newNotes, $newProfile, $newStatus, 0.0, 1, $newProfileCompletion,$newFriendCount)")
				or die(mysql_error());

}
mysql_close($connection);
 //echo "DONE";
//header( 'Location: http://apps.facebook.com/comicavatar/main.php');
//echo "<fb:redirect url=\"main.php\" />";

?>
