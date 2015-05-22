<?php
header("Access-Control-Allow-Origin: *");
session_start();
require_once("twitteroauth.php");

$nm = uniqid();
$txt =$_POST['txt'];

//echo $nm;

    $_SESSION['txt']=$txt;

// Consumer keyの値
$consumer_key = "2QCGxWNOaR9P5zPSnKggl1kqM";
// Consumer secretの値
$consumer_secret = "q1E89M5CRsN3aHvrJ97qKctFpkJNhKDM9GcpHkUMKX3IrQrVTI";

    // OAuthオブジェクト生成
    $to = new TwitterOAuth($consumer_key,$consumer_secret);

    // callbackURLを指定してRequest tokenを取得
    $tok = $to->getRequestToken("http://54.148.224.187/tools/php/callback.php");

    // セッションに保存
    $_SESSION['request_token']=$token=$tok['oauth_token'];
    $_SESSION['request_token_secret'] = $tok['oauth_token_secret'];

    // サインインするためのURLを取得
    $url = $to->getAuthorizeURL($token);

	//print '<META http-equiv="refresh" CONTENT="0;URL=';
	print $url;
	//print '">';
?>


