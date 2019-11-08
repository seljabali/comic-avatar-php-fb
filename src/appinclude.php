<?php
require_once 'facebook.php';

$appapikey = '****';
$appsecret = '****';
$facebook = new Facebook($appapikey, $appsecret);
$user = $facebook->require_login();

//[todo: change the following url to your callback url]
$appcallbackurl = 'http://pentagon.cs.berkeley.edu/~cs160-at/';  

//catch the exception that gets thrown if the cookie has an invalid session_key in it
try {
  if (!$facebook->api_client->users_isAppAdded()) {
    $facebook->redirect($facebook->get_add_url());
  }
} catch (Exception $ex) {
  //this will clear cookies for your application and redirect them to a login prompt
  $facebook->set_user(null, null);
  $facebook->redirect($appcallbackurl);
}
