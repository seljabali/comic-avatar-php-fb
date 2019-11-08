<?php
// code that will be executed if the form has been submitted:

if ($submit) {

    // connect to the database
    // (you may have to adjust the hostname,username or password)

//database info
$db = array('host' => 'localhost:/home/cc/cs160/fa07/class/cs160-at/var/data/cs160-at.sock',
       'user' => 'root',
       'pass' => '****'); 

//connect to the database server
$conn = mysql_connect($db['host'], $db['user'], $db['pass']); 

//report the connection failure or success
if (!$conn) {
   die("there was a problem connecting to the database.");
}

    mysql_select_db("binary_data", $conn);

    $data = addslashes(fread(fopen($form_data, "r"), filesize($form_data)));

    $result=MYSQL_QUERY("INSERT INTO binary_data (description,bin_data,filename,filesize,filetype) ".
        "VALUES ('$form_description','$data','$form_data_name','$form_data_size','$form_data_type')");

    $id= mysql_insert_id();
    print "<p>This file has the following Database ID: <b>$id</b>";

    MYSQL_CLOSE();

} else {

    // else show the form to submit new data:
?>

    <form method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data">
    File Description:<br>
    <input type="text" name="form_description"  size="40">
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
    <br>File to upload/store in database:<br>
    <input type="file" name="form_data"  size="40">
    <p><input type="submit" name="submit" value="submit">
    </form>

<?php

}

?>
