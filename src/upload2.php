<?php
    // check if a file was submitted
	echo $_POST['user_id'];
    if(!isset($_FILES['userfile'])) {
	print_r($_POST);
        print_r($_FILES);
	echo '<p>Please select a file</p>';
    }
    else
        {
	echo 'Submitting file';
        try {
            upload();
            // give praise and thanks to the php gods
            echo '<p>Thank you for submitting</p>';
        }
        catch(Exception $e) {
            echo $e->getMessage();
            echo 'Sorry, could not upload file';
        }
    }
?>

<?php
    // the upload function
    function upload(){
    $maxsize = 1000000;
	echo "<br>check 1"; 
    if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
 	echo "<br>check 2";
	echo $_FILES['userfile']['size']; 
        // check the file is less than the maximum file size
        if($_FILES['userfile']['size'] < $maxsize)
            {
	echo "<br>check 2";
        // prepare the image for insertion
        $imgData =addslashes (file_get_contents($_FILES['userfile']['tmp_name']));
        // $imgData = addslashes($_FILES['userfile']);
 
        // get the image info..
          $size = getimagesize($_FILES['userfile']['tmp_name']);
 
        // put the image in the db...
          $db = array('host' => 'localhost:/home/cc/cs160/fa07/class/cs160-at/var/data/cs160-at.sock',
       'user' => 'root',
       'pass' => 'nikita'); 

//connect to the database server
$conn = mysql_connect($db['host'], $db['user'], $db['pass']); 

//report the connection failure or success
if (!$conn) {
   die("there was a problem connecting to the database.");
}

mysql_select_db("binary_data", $conn) OR DIE ("Unable to select db".mysql_error());
 
echo "I AM HERE!";

        // our sql query
	$sql = "DELETE FROM testblob WHERE user_id=" . $_POST[user_id];
	@mysql_query($sql);

        $sql = "INSERT INTO testblob
                ( image_id , image_type ,image, image_size, image_name, user_id, avatar_name)
                VALUES
                ('', '{$size['mime']}', '{$imgData}', '{$size[3]}', '{$_FILES['userfile']['name']}', '{$_POST[user_id]}', '{$_POST['avatar_name']}' )";
 
        // insert the image 
        if(!mysql_query($sql)) {
            echo 'Unable to upload file';
            }
        }
    }
    else {
         // if the file is not less than the maximum allowed, print an error
         echo
          '<div>File exceeds the Maximum File limit</div>
          <div>Maximum File limit is '.$maxsize.'</div>
          <div>File '.$_FILES['userfile']['name'].' is '.$_FILES['userfile']['size'].' bytes</div>
          <hr />';
         }
    }

header( 'Location: http://www.yoursite.com/new_page.html' );
?>

