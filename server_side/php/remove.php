<?php
session_id($_GET['PHPSESSID']);
session_start();
require_once("twitteroauth.php");
require './tmhOAuth.php';
require './tmhUtilities.php';



$oauth_access_token = $_SESSION['oauth_access_token'];
$oauth_access_token_secret = $_SESSION['oauth_access_token_secret'];


// Consumer keyの値
$consumer_key = "Z4megDutleofnn9exjcWrDJDj";
// Consumer secretの値
$consumer_secret = "vVEjTOACVwRVLlAQ2PQcGhVB2VHDO3mw1BIrPAglRVIBzG2vI7";
// Access Tokenの値
$access_token = $oauth_access_token;
// Access Token Secretの値
$access_token_secret = $oauth_access_token_secret;


$tmhOAuth = new tmhOAuth(array(
'consumer_key'    => $consumer_key,
'consumer_secret' => $consumer_secret,
'user_token'      => $access_token,
'user_secret'     => $access_token_secret,
));


$code = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/account/verify_credentials.json');

//$code2 = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/followers/ids.json?cursor=-1&screen_name=qanta&count=5000');



if ($code == 200) {
	//tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
	$jsp = json_decode($tmhOAuth->response['response']);
	$tname = $jsp ->{'screen_name'};
	$tid = $jsp ->{'id'};
} else {
	//tmhUtilities::pr($tmhOAuth->response['response']);
}


$link = mysql_connect("goodbye.ceyiw7ismype.us-west-2.rds.amazonaws.com:3306","qanta", "suta0220");
if (!$link) {
    die('failed' . mysql_error());
}
$dbname = "goodbye";
$tblname="users";

mysql_select_db($dbname,$link);

$res_result = mysql_query( "delete from $tblname where id = $tid;", $link);

		print '<META http-equiv="refresh" CONTENT="0;URL=';
		print 'http://www.adultswim.com/etcetera/goodbye-unfollower/deactivate.html';
		print '">';


?>