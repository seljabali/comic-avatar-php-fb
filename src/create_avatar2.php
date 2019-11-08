<fb:dashboard>
     <fb:action href="main.php">Home</fb:action>
     <fb:action href="stats.php">Statistics</fb:action>
	<fb:action href="create_avatar2.php">Create Avatar</fb:action>
    <fb:action href="invite.php">Invite Friends</fb:action>
</fb:dashboard>

<?
require_once 'appinclude.php';
if($_POST['avatar_name']){
// connect to DB
//$db = array('host' => 'localhost:/home/cc/cs160/fa07/class/cs160-at/var/data/cs160-at.sock',
//'user' => 'root',
//'pass' => 'nikita'); 
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

$sql = "DELETE FROM user_data WHERE user_id=" . $user;
@mysql_query($sql);
$check_query = mysql_query("SELECT * FROM visit WHERE name = $user");
$check = mysql_fetch_array($check_query);
if(!$check)
{
	$sql = "DELETE FROM visit WHERE name=" . $user;
	@mysql_query($sql);
	$sql2 = "INSERT INTO visit
		(name, sender, receiver, available, time)
		VALUES
		($user, 0, 0, 'yes', 0)";
	@mysql_query($sql2);
}	

$sql = "INSERT INTO user_data
	(user_id, avatar_name, avatar_pic)
	VALUES
	($user, '{$_POST['avatar_name']}', '{$_GET['img']}')";

if(!mysql_query($sql)) {
$fbml = <<<EndHereDoc
<fb:error>  
<fb:message>Sorry...</fb:message>  
Could not create Avatar, please try again.
</fb:error>
EndHereDoc;
echo $fbml;
}else{
$fbml = <<<EndHereDoc
<fb:success>  
<fb:message>Congratulations!</fb:message>  
Avatar created successfully.
</fb:success>
EndHereDoc;
echo $fbml;
}
}else{
	
if($_GET['img'] < 11 && $_GET['img'] > 0)
{
$img = $_GET['img'];
$image_path = 'http://www.fbcomics.com/avatar/pics/level1/c'.$img.'lvl1.jpg';
$fbml = <<<EndHereDoc
<fb:editor action="#" labelwidth="100">
<fb:editor-text label="Avatar Name" name="avatar_name" value=""/>
<H1 align="center"><img src=$image_path border=0 height="150" width="150"/><br/></H1>
<fb:editor-buttonset>
<fb:editor-button value="Create Avatar!"/>
</fb:editor-buttonset>
</fb:editor>
EndHereDoc;
echo $fbml;
}else{
echo "<p><font size='5' face='Verdana'>Please select an avatar:</font></p><br/><br><br>";
$NUMBER_AVATARS = 10;
for($loop = 1; $loop <= $NUMBER_AVATARS; $loop++){
$image_path = 'http://www.fbcomics.com/avatar/pics/level1/c'.$loop.'lvl1.jpg';
$fbml = <<<EndHereDoc
<a href="?img=$loop"><img src=$image_path border=0 height="150" width="150"></a>
EndHereDoc;
echo $fbml;
}
}
}
?>
