<?php
    // again we check the $_GET variable
    if(isset($_GET['image_id']) && is_numeric($_GET['image_id'])) {
        $sql = "SELECT image_type, image_size, image_name FROM testblob WHERE image_id=".$_GET['image_id'];
 
        // put the image in the db...
          $db = array('host' => 'localhost:/home/cc/cs160/fa07/class/cs160-at/var/data/cs160-at.sock',
       'user' => '****',
       'pass' => '****'); 

//connect to the database server
$link = mysql_connect($db['host'], $db['user'], $db['pass']); 

//report the connection failure or success
if (!$link) {
   die("there was a problem connecting to the database.");
}

mysql_select_db("binary_data") OR DIE ("Unable to select db".mysql_error());
 
        $result = mysql_query($sql)  or die("Invalid query: " . mysql_error());
 
        while($row=mysql_fetch_array($result)) {
            echo 'This is '.$row['image_name'].' from the database<br />';
            echo '<img '.$row['image_size'].' src="view.php?image_id='.$_GET['image_id'].'">';
        }
    }
    else {
        echo 'File not selected';
    }
?>
