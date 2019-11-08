<fb:dashboard>
     <fb:action href="main.php">Home</fb:action>
     <fb:action href="stats.php">Statistics</fb:action>
	<fb:action href="create_avatar.php">Upload Image</fb:action>
    <fb:action href="invite.php">Invite Friends</fb:action>
</fb:dashboard>

<?
require_once 'appinclude.php';
echo $_POST['img'];
echo "</br>";
echo $_POST['avatar_name'];
echo "</br>";
echo $_POST['user_id'];



if($_POST['avatar_name']){
// connect to DB
//$db = array('host' => 'localhost:/home/cc/cs160/fa07/class/cs160-at/var/data/cs160-at.sock',
//'user' => 'root',
//'pass' => '****'); 
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
$sql = "INSERT INTO user_data
	(user_id, avatar_name, avatar_pic)
	VALUES
	($user, '{$_POST['avatar_name']}', '{$_GET['img']}')";
if(!mysql_query($sql)) {
	echo "Failed to create Avatar!";
}
}else{
	echo "Error: User ID is blank!";
}
?>
