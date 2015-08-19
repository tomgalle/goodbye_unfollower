<?php
session_id(uniqid());
session_start();
header("Access-Control-Allow-Origin: *");
require_once("twitteroauth.php");

$nm = uniqid();
$txt =$_POST['txt'];

//echo $nm;

    $_SESSION['txt']=$txt;

// Consumer keyの値
$consumer_key = "Z4megDutleofnn9exjcWrDJDj";
// Consumer secretの値
$consumer_secret = "vVEjTOACVwRVLlAQ2PQcGhVB2VHDO3mw1BIrPAglRVIBzG2vI7";

    // OAuthオブジェクト生成
    $to = new TwitterOAuth($consumer_key,$consumer_secret);

    // callbackURLを指定してRequest tokenを取得
    $tok = $to->getRequestToken("http://www.adultswim.com/etcetera/goodbye-unfollower/php/callback_remove.php?".SID);

    // セッションに保存
    $_SESSION['request_token']=$token=$tok['oauth_token'];
    $_SESSION['request_token_secret'] = $tok['oauth_token_secret'];

    // サインインするためのURLを取得
    $url = $to->getAuthorizeURL($token);

	//print '<META http-equiv="refresh" CONTENT="0;URL=';
	print $url;
	//print '">';
?>


