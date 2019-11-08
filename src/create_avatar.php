<fb:dashboard>
     <fb:action href="main.php">Home</fb:action>
     <fb:action href="stats.php">Statistics</fb:action>
	<fb:action href="create_avatar.php">Upload Image</fb:action>
    <fb:action href="invite.php">Invite Friends</fb:action>
</fb:dashboard>

<? 
require_once 'appinclude.php';
?>
<form enctype="multipart/form-data" action="http://pentagon.cs.berkeley.edu/~cs160-at/avatar/upload2.php" method="post">
Avatar name:<br>
<input type="text" name="avatar_name"  size="40"><br>
Image Location: <br>
<input type="hidden" name="user_id" value=<? echo $user; ?> />
<input name="userfile" type="file" />
<input type="submit" value="Send File" />
</form>
