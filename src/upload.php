        <h3>Please Choose a File and click Submit</h3>
 
        <form enctype="multipart/form-data" action="http://pentagon.cs.berkeley.edu/~cs160-at/avatar/upload2.php" method="post">
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
            <input name="userfile" type="file" />
            <input type="submit" value="Send File" />
        </form>
