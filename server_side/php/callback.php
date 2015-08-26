<?php
session_id($_GET['PHPSESSID']);
session_start();
require_once("twitteroauth.php");

$consumer_key = "Z4megDutleofnn9exjcWrDJDj";

$consumer_secret = "vVEjTOACVwRVLlAQ2PQcGhVB2VHDO3mw1BIrPAglRVIBzG2vI7";

$verifier = $_GET['oauth_verifier'];

$to = new TwitterOAuth($consumer_key,$consumer_secret,$_SESSION['request_token'],$_SESSION['request_token_secret']);




$access_token = $to->getAccessToken($verifier);

$_SESSION['oauth_access_token']=$access_token['oauth_token'];
$_SESSION['oauth_access_token_secret']=$access_token['oauth_token_secret'];


	print '<META http-equiv="refresh" CONTENT="0;URL=';
	print 'http://www.adultswim.com/etcetera/goodbye-unfollower/php/regist.php?'.SID.'&num='.$_SESSION['num'];
	print '">';


?>
