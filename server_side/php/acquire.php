<?
session_start();
require_once("twitteroauth.php");

// Consumer keyの値
$consumer_key = "j5iwIYbo0pPK6OJ5Dpoig";
// Consumer secretの値
$consumer_secret = "vyNQLEFEkUCoTlvbZsZGQ9GKl1fc6M63sKz7i5nIsM";

    // OAuthオブジェクト生成
    $to = new TwitterOAuth($consumer_key,$consumer_secret);

    // callbackURLを指定してRequest tokenを取得
    $tok = $to->getRequestToken("http://www.shiroari.com/important/php/callback_cookie.php");

    // セッションに保存
    $_SESSION['request_token']=$token=$tok['oauth_token'];
    $_SESSION['request_token_secret'] = $tok['oauth_token_secret'];

    // サインインするためのURLを取得
    $url = $to->getAuthorizeURL($token);

	print '<META http-equiv="refresh" CONTENT="0;URL=';
	print $url;
	print '">';
?>


