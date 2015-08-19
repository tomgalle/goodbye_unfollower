<?php
session_id($_GET['PHPSESSID']);
session_start();
require_once("twitteroauth.php");
require './tmhOAuth.php';
require './tmhUtilities.php';

$num =$_GET['num'];

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

$res_result = mysql_query( "select count(*) from $tblname where id = $tid;", $link);

$result = mysql_fetch_array($res_result, MYSQL_ASSOC);

	if($result["count(*)"]==0&&$tid==true){
		$res_newrec = mysql_query( "INSERT INTO `goodbye`.`users` (`id`, `tokensecret`, `token`, `lim`) VALUES ('$tid','$access_token_secret','$access_token','$num');", $link);


		$followers = "";
		$follows = getFollowers("-1");


		while ($follows != "0") {
	
			$follows = getFollowers($follows);
		}


		$file = "/var/www/html/tools/php/followers/".$tid.".csv";

		file_put_contents($file, $followers);
		chmod($file, 0777);
		mysql_close($link);


		print '<META http-equiv="refresh" CONTENT="0;URL=';
		print 'http://www.adultswim.com/etcetera/goodbye-unfollower/activated.html?num='.$num;
		print '">';

	}else{


		$res_exist = mysql_query( "select * from $tblname where id = $tid;", $link);
		while($row = mysql_fetch_array($res_exist, MYSQL_ASSOC)){
			$ulim = $row['lim'];
		}
		mysql_close($link);

		print '<META http-equiv="refresh" CONTENT="0;URL=';
		print 'http://www.adultswim.com/etcetera/goodbye-unfollower/alreadyactivated.html?num='.$ulim;
		print '">';

	}




function getFollowers($cs){
	global $tmhOAuth;
	global $followers;
	global $tname;
	//echo 'https://api.twitter.com/1.1/followers/ids.json?screen_name='.$tname.'&cursor='.$cs.'&count=5000';
	//$code = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/followers/ids.json?screen_name='.$tname.'&cursor='.$cs.'&count=5000');
	$code = $tmhOAuth->request(
		'GET', 
		$tmhOAuth->url('1.1/followers/ids'), 
		array(
			'screen_name' => $tname,
			'count' => '5000',
			'cursor'=> $cs
		)
	);
	//echo $code;
	if ($code == 200) {
		$jsp = json_decode($tmhOAuth->response['response']);
		$cursor = $jsp ->{'next_cursor_str'};
		$len = count($jsp ->{'ids'});
		for ($count = 0; $count < $len; $count++){
			if($followers != ""){
				$followers .= ",";
			}
			$id = $jsp ->{'ids'}[$count];
			$followers .= $id;
		}
		return $cursor;
	} else {
		return "0";
		//tmhUtilities::pr($tmhOAuth->response['response']);
	}
}

?>