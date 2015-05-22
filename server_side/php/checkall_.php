<?php
require_once("twitteroauth.php");
require './tmhOAuth.php';
require './tmhUtilities.php';


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
		$list = explode(",", $prev);
		//print count($list);
		for ($count = 0; $count < count($list); $count++){
			if (!strstr($followers, $list[$count])) {
				echo $list[$count];
				
				$user = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/users/show.json',
				array(
					'user_id' => $list[$count]
				)
				);

				if ($user == 200) {
					$jsp = json_decode($tmhOAuth->response['response']);
					$tname = $jsp ->{'screen_name'};
					//echo $tname;
				} else {
				}

				
				$url = 'http://54.148.224.187/tools/php/gen.php';
				$data = array(
					'nm' => $tname
				);
				$data = http_build_query($data, "", "&");
				$header = array(
					"Content-Type: application/x-www-form-urlencoded",
					"Content-Length: ".strlen($data)
				);
				$options = array('http' => array(
					'method' => 'POST',
					'header' => implode("\r\n", $header),
					'content' => $data
				));
				$contents = file_get_contents($url, false, stream_context_create($options));
				echo $contents;

				$image = "/var/www/html/tools/twimg/".$contents.".png";
				$body = "Goodbye! @".$tname;

				$code = $tmhOAuth->request('POST', 'https://api.twitter.com/1.1/statuses/update_with_media.json',
				array(
					'media[]'  => file_get_contents($image),
					'status' => $body
				),
				true,
				true
				);
				if ($tmhOAuth->response["code"] == 200){ 
					//var_dump($tmhOAuth->response["response"]);
					echo "success";
				} else {
					var_dump($tmhOAuth->response["code"]);
					echo $tmhOAuth->response["code"];
				}
			} 
		}

		$file = "./followers/".$tid.".csv";
		file_put_contents($file, $followers);
		echo "msend";
}

mysql_close($link);

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