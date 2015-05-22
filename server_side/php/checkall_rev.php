<?php
require_once("twitteroauth.php");
require './tmhOAuth.php';
require './tmhUtilities.php';



$stat = 'stat.txt';
$current = file_get_contents($stat);

echo $current;

if($current == "0"){
$current = "1";
file_put_contents($stat, $current);

$link = mysql_connect("goodbye.ceyiw7ismype.us-west-2.rds.amazonaws.com:3306","qanta", "suta0220");
if (!$link) {
    die('failed' . mysql_error());
}
$dbname = "goodbye";
$tblname="users";

mysql_select_db($dbname,$link);





$res_result = mysql_query( "SELECT * FROM  $tblname;", $link);


while($row = mysql_fetch_array($res_result, MYSQL_ASSOC)){
		// Consumer keyの値
		$consumer_key = "2QCGxWNOaR9P5zPSnKggl1kqM";
		// Consumer secretの値
		$consumer_secret = "q1E89M5CRsN3aHvrJ97qKctFpkJNhKDM9GcpHkUMKX3IrQrVTI";
		// Access Tokenの値
		$access_token = $row['token'] ;
		// Access Token Secretの値
		$access_token_secret = $row['tokensecret'];
		$tmhOAuth = new tmhOAuth(array(
			'consumer_key'    => $consumer_key,
			'consumer_secret' => $consumer_secret,
			'user_token'      => $access_token,
			'user_secret'     => $access_token_secret,
		));


		$tid =  $row['id'];

		$followers = "";
		$follows = getFollowers("-1");


		while ($follows != "0") {
	
			$follows = getFollowers($follows);
		}
		$prev = file_get_contents('./followers/'.$tid.'.csv');
		//echo $prev;
		$list = explode(",", $prev);
		//print count($list);
		for ($count = 0; $count < count($list); $count++){
			if (!strstr($followers, $list[$count])) {
				echo $list[$count];

				
			} 
		}

		$file = "./followers/".$tid.".csv";
		file_put_contents($file, $followers);
		echo "msend";
}

mysql_close($link);


$current = "0";
file_put_contents($stat, $current);

}else{
	echo "canceled";
}

function getFollowers($cs){
	global $tmhOAuth;
	global $followers;
	global $tid;
	//echo 'https://api.twitter.com/1.1/followers/ids.json?screen_name='.$tname.'&cursor='.$cs.'&count=5000';
	//$code = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/followers/ids.json?screen_name='.$tname.'&cursor='.$cs.'&count=5000');
	$code = $tmhOAuth->request(
		'GET', 
		$tmhOAuth->url('1.1/followers/ids'), 
		array(
			'user_id' => $tid,
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
		//tmhUtilities::pr($tmhOAuth->response['response']);
	}
}

?>