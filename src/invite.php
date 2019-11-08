<fb:dashboard>
     <fb:action href="main.php">Home</fb:action>
     <fb:action href="stats.php">Statistics</fb:action>
	<fb:action href="create_avatar2.php">Create Avatar</fb:action>
    <fb:action href="invite.php">Invite Friends</fb:action>
</fb:dashboard>

<?php
//$user = $facebook->require_login();
$invfbml = <<<FBML
Hey, I just added a personalized avatar to my profile!\n
   I really think you should try it out!
<fb:req-choice url="http://apps.facebook.com/comicavatar"  label="Add Comic Avatar" />
FBML;
?>

<fb:fbml>
<fb:request-form
action="main.php"
method="POST"
invite="true"
type="Comic Avatar"
content="<? echo htmlentities($invfbml); ?>">

<fb:multi-friend-selector
showborder="false"
actiontext="Invite your friends."
rows="5"
max="20">
</fb:request-form>
</fb:fbml> 	
