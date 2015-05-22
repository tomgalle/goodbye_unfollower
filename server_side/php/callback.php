<?php
session_start();
require_once("twitteroauth.php");

$consumer_key = "2QCGxWNOaR9P5zPSnKggl1kqM";
$consumer_secret = "q1E89M5CRsN3aHvrJ97qKctFpkJNhKDM9GcpHkUMKX3IrQrVTI";


$verifier = $_GET['oauth_verifier'];

$to = new TwitterOAuth($consumer_key,$consumer_secret,$_SESSION['request_token'],$_SESSION['request_token_secret']);

$access_token = $to->getAccessToken($verifier);

$value = $access_token['oauth_token'];
$value .= ",";
$value .= $access_token['oauth_token_secret'];




$timeout = time() + 1800 * 86400;
setcookie("importanttw",$value,$timeout,'/','54.148.224.187');

	print '<META http-equiv="refresh" CONTENT="0;URL=';
	print 'http://54.148.224.187/tools/php/regist.php';
	print '">';

?>
