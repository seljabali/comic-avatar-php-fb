<?php
    // just so we know it is broken
    error_reporting(E_ALL);
    // some basic sanity checks
    if(isset($_GET['image_id']) && is_numeric($_GET['image_id'])) {
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

        // get the image from the db
        $sql = '' . "SELECT image FROM testblob WHERE user_id=" . $_GET['image_id'] . '';

        // the result of the query
        $result = mysql_query("$sql") or die("Invalid query: " . mysql_error());
 
        // set the header for the image
        header("Content-type: image/jpeg");
        echo mysql_result($result, 0);
 
        // close the db link
        mysql_close($link);
    }
    else {
        echo 'Please use a real id number';
    }
?>
