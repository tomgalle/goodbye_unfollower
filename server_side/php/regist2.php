<?php
require_once("twitteroauth.php");
require './tmhOAuth.php';
require './tmhUtilities.php';

$rt = isset($_COOKIE["importanttw"]);

if(isset($_COOKIE["importanttw"])!=""){
	list($oauth_access_token,$oauth_access_token_secret,$dataurl,$txt)=explode(",",$_COOKIE["importanttw"]);
}else{
	echo "bb";
}


// Consumer keyの値
$consumer_key = "GsUAX7mFZIB5efbwiOvwTQhBv";
// Consumer secretの値
$consumer_secret = "K5QXXwhGuurEeKDxJLoXlkVtmGikrWQSUY6ehz8tYJuHaJ7zTS";
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

$image = $dataurl;

$code = $tmhOAuth->request('POST', 'https://api.twitter.com/1.1/statuses/update_with_media.json',

array(
'media[]'  => file_get_contents($image),
'status'   => $txt
),true,true);

$value ="";
$timeout = time() -1800;
setcookie("importanttw",$value,$timeout,'/','superimportanttweet.com');

//echo $code;

if ($code == 200) {
	//tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
	$jsp = json_decode($tmhOAuth->response['response']);
	//echo $jsp["id_str"];
	$lnk =  'http://twitter.com/'.$jsp["user"]["screen_name"].'/status/'.$jsp["id_str"];
	
	header("Location: $lnk");
} else {
	//tmhUtilities::pr($tmhOAuth->response['response']);
	$lnk = "http://superimportanttweet.com";
	header("Location: $lnk");
}



//header('Location: http://www.shiroari.com/') ;

function json_decode($json)
{
    $def = array(
        '/[\r\n]/' => '',
        '/([{},\[\]])/' => "$1\n",
        '/^([^:]+):/m' => '$1 =>',
        '/([{\[])/' => 'array(',
        '/([}\]])/' => ')',
        '/\\\([\/])/' => '$1',
        '/\\\u([0-9a-f]{4})/e' => "mb_convert_encoding(pack('V', hexdec('U$1')), 'UTF-8', 'UCS-4LE')"
    );

    $p = array_keys($def);
    $r = array_values($def);
    $s = preg_replace($p, $r, $json);

    eval('$res = ' . $s . ';');

    return $res;
}



?>