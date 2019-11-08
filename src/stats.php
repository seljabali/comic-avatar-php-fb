<fb:dashboard>
     <fb:action href="main.php">Home</fb:action>
     <fb:action href="stats.php">Statistics</fb:action>
	<fb:action href="create_avatar2.php">Create Avatar</fb:action>
    <fb:action href="invite.php">Invite Friends</fb:action>
</fb:dashboard>
<?
define("MaxPoints", 100);
define("MaxLevel", 3);
define("StatusPoints", 30);
define("WallPoints", 80);
define("NotesPoints", 70);
define("ProfileUpdate", 50);
define("Completion",1);
define("ProfileElements:", 25);
define("Friends", 1);
function calcPoints($new, $old, $type){
	
	$Diff = $new - $old;
	if ($Diff <= 0)
		return 0;
	switch ($type){
	case "wall":
		$calc = $Diff * WallPoints;
		return $calc;
		break;
	case "notes":
		$calc = NotesPoints * $Diff;
		return $calc;
		break;
	case "status":
		$calc = StatusPoints;
		return StatusPoints;
		break;
	case "profile":
		$calc = ProfileUpdate;
		return $calc;
		break;
	case "completion":
		$calc = Completion * $Diff;
		return $calc;
		break;
	case "friends":
		$calc = Friends * $Diff;
		return $calc;
		break;		
	}
}
function connectToDB(){
	//$db = array('host' => 'localhost:/home/cc/cs160/fa07/class/cs160-at/var/data/cs160-at.sock',
	//	   		'user' => 'root',
	//	  		'pass' => '****');

	$db = array('host' => 'localhost.fbcomics.com',
		   		'user' => '****',
		  		'pass' => '****');
	
	$conn = mysql_connect($db['host'], $db['user'], $db['pass']); 
	if (!$conn){
		die("There was a problem connecting to the database.");
	}
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
function Bar($percent, $top, $left){
$bar = 25;
$percent  = $percent*$bar*0.01;
//echo "$percent<br />";
$hideBox = FALSE;
if ($percent  == 0)
	$hideBox = TRUE;
$percent  = $percent."em";
echo "<html>\n";
echo "<head>\n";
echo "<style type='text/css'>\n";
echo "body {\n";
echo " font:76% normal verdana,arial,tahoma;\n";
echo "}\n";
echo "#Box {\n";
echo "position:absolute;\n";
echo "left:$left;\n";
echo "top:$top;\n"; //TOP
echo "width:25em;\n"; 
echo "line-height:1em;\n";
echo "background:#FFFFFF;\n";
echo "border:0.5px solid #003366;\n";
echo "white-space:nowrap;\n";
echo "padding:0.5em;\n";
echo "}";
if (!$hideBox){
echo "#Box2 {\n";
echo " position:absolute;\n";
echo " left:$left;\n";
echo " top:$top;\n"; //TOP
echo " width:$percent;\n"; 
echo " line-height:1em;\n";
echo " background:#99ccff;\n";
echo " border:0.5px solid #003366;\n";
echo " white-space:nowrap;\n";
echo " padding:0.5em;\n";
echo "}\n";
}
echo "</style>\n";
echo "<script type='text/javascript'>\n";
echo "var box = null;\n";
if (!$hideBox)
	echo "var box2 = null;\n";
echo "function init() {\n";
echo "  box = document.getElementById('Box');\n";
if (!$hideBox)
	echo "  box2 = document.getElementById('Box2');\n";
//echo "  box.style.left = '0px';\n";
//echo "  box.style.height = 100;\n";
//echo "  box2.style.left = '0px';\n";
//echo "  box2.style.height =100;\n";
echo "}\n";
echo "window.onload = init;\n";
echo "</script>\n";
echo "</head>\n";
echo "<body>\n";
echo "<div id='Box'>\n";
echo "</div>\n";
if (!$hideBox){
	echo "<div id='Box2'>\n";
	echo "</div>\n";
}
echo "</body>\n";
echo "</html>";
}

require_once 'appinclude.php';

//Initialize Query
$api_key = "****";
$secret = "****";

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
$user = $facebook->require_login();//$user = "1241699";

//Queries
$getProfile = "SELECT uid, first_name, last_name, name, pic, affiliations, profile_update_time, religion, birthday, sex, hometown_location, meeting_sex, meeting_for, relationship_status, political, current_location, activities, interests, is_app_user, music, tv, movies, books, quotes, about_me, hs_info, education_history, work_history, notes_count, wall_count, status, has_added_app
		FROM user
		WHERE uid = $user";	
$getFriends = "SELECT uid2 FROM friend WHERE uid1=$user";

//$getGroups = "SELECT gid, name, nid, description, group_type, group_subtype,
 //      pic, pic_big, pic_small, creator, update_time,
//			FROM group 
//			WHERE gid IN (SELECT gid FROM group_member WHERE uid=$user) AND
//      			  gid IN (gids)";

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


//Get Stored USER INFO
$connection = connectToDB();
if (!$connection)
	echo "Couldn't Connect to Database<br />";

$result = mysql_query("SELECT uid, wallposts,complete, friends, notes,profile,status,points,level 
					   FROM stats 
					   WHERE uid = $user");
					
$row = mysql_fetch_array($result);
if ($user == $row['uid']){
	$oldWall =    $row['wallposts'];
	$oldNotes =   $row['notes'];
	$oldProfile = $row['profile'];
	$oldStatus =  $row['status'];
	$oldPoints =  $row['points'];
	$oldLevel =   $row['level'];
	$oldFriendCount = $row['friends'];
	$oldProfileCompletion = $row['complete'];
	

	//Caculate Points
	$gainedPoints = 0;
	$gainedPoints = calcPoints($newWall, $oldWall, "wall") + calcPoints($newNotes, $oldNotes, "notes") + calcPoints($newStatus, $oldStatus, "status") +
					calcPoints($newProfile, $oldProfile, "profile")+ calcPoints($newProfileCompletion, $oldProfileCompletion, "completion")+
					calcPoints($newFriendCount,$oldFriendCount,"friends");
	
	//Update Level
	$gotLeveled = FALSE;
	$updatedLevel = $oldLevel;
	$updatedPoints = $oldPoints + $gainedPoints;
	if ($updatedPoints >= MaxPoints){
		$updatedLevel = $oldLevel + floor($updatedPoints / MaxPoints);
		$updatedPoints = $updatedPoints % MaxPoints;
		$gotLeveled = TRUE;
		if ($updatedLevel > MaxLevel)
			$updatedLevel = MaxLevel;
		echo "<fb:success message='You Are Now Level $updatedLevel!' />";
	}
	$nextLevel = $updatedPoints;
	
	//Output
	if ($gainedPoints == 0)
		echo "<font size='3' color='blue'>  Recently Gained No Points</font>";
	else{
		$newLines = 0;
		echo "<font size='3' color='blue'>  Recently Gained $gainedPoints Points</font>";		
	}
	if (calcPoints($newWall, $oldWall, "wall") > 0)
		echo "<br /><font size='1' color='blue'>  Wall Posts: $oldWall->$newWall</font>"; 	
	else
		$newLines += 1;
	if (calcPoints($newNotes, $oldNotes, "notes") > 0)
		echo "<br /><font size='1' color='blue'>  Posted Notes: $oldNotes->$newNotes</font>"; 		
	else
		$newLines += 1;
	if (calcPoints($newStatus, $oldStatus, "status") > 0)
		echo "<br /><font size='1' color='blue'>  Status Recently Updated</font>"; 
	else
		$newLines += 1;
	if (calcPoints($newProfile, $oldProfile, "profile") > 0)
		echo "<br /><font size='1' color='blue'>  Profile Recently Updated</font>"; 
	else
		$newLines += 1;
	if (calcPoints($newProfileCompletion, $oldProfileCompletion, 'completion') > 0)
		echo "<br /><font size='1' color='blue'>  Wall Completion: $oldProfileCompletion%->$newProfileCompletion%</font>"; 
	else
		$newLines += 1;
	if (calcPoints($newFriendCount,$oldFriendCount,"friends") > 0)
		echo "<br /><font size='1' color='blue'>  Friends: $oldFriendCount->$newFriendCount</font>"; 
	else
		$newLines += 1;

	for ($i=0; $i < $newLines; $i++)
		echo "<br />";		
		

	echo "<br /><br />";

	$max = MaxPoints;
	if ($updatedLevel >= MaxLevel){
		echo "<font size='3' color='blue'>Level: $updatedLevel (King of your kind)</font>";
	}
	else{
		$nextLevel = $updatedLevel + 1;
		$temp = $max - $updatedPoints;
		echo "<font size='3' color='blue'>Current Level: $updatedLevel<br /></font>
			  <font size='2' color='blue'>$temp points to reach level $nextLevel</font><br /><br /><br /><br />";
		if ($gotLeveled){
			$t = 29;
			$t .= "em";
			$l = 270;
			$l .= "px";
		}
		else{
			$t = 24;
			$t .="em";
			$l = 270;
			$l .= "px";
		}
		Bar($updatedPoints,$t, $l);
	}

	//Update DataBase
	mysql_query ("UPDATE stats SET
				  uid=$user, wallposts=$newWall, notes=$newNotes, profile=$newProfile, status=$newStatus, 
				  points=$updatedPoints, level=$updatedLevel, complete = $newProfileCompletion, friends = $newFriendCount
				  WHERE uid = $user") 
				or die(mysql_error());
}	
mysql_close($connection);
?>

