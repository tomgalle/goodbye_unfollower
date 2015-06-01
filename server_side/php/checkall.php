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
		$ulim = $row['lim'];
		$org_ulim = $row['lim'];
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


		$ncode = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/users/show.json',
		array(
			'user_id' => $tid
		)
		);

		if ($ncode == 200) {
			$jsps = json_decode($tmhOAuth->response['response']);
			$uname = $jsps ->{'screen_name'};
			//echo $uname;
		

			$plim = 0;
			//print count($list);
			//echo "aaa".$followers;
			$flist = explode(",", $followers);
			if($followers != $prev){
				if($followers != "" && abs(count($flist)-count($list))<20){

				for ($count = 0; $count < count($list); $count++){
					if($list[$count] == true){
					if($plim < 5 && $ulim >0){
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
								$frnum = $jsp ->{'friends_count'};
								$following = $jsp ->{'following'};
						
								
						
								$url = 'http://54.148.224.187/tools/php/gen_img.php';
								$ord = $count+1;
								$txt = getDesc();
								$data = array(
									'nm' => $tname,
									'txt' => $txt
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
								$body = "Goodbye @".$tname." , please follow me again sometime. Love, @".$uname;

								$code = $tmhOAuth->request('POST', 'https://api.twitter.com/1.1/statuses/update_with_media.json',
								array(
									'media[]'  => file_get_contents($image),
									'status' => $body
								),
								true,
								true
								);
								if ($tmhOAuth->response["code"] == 200){ 
									echo "success";
								} else {
									var_dump($tmhOAuth->response["code"]);
									echo $tmhOAuth->response["code"];
							}
				
								$plim = $plim+1;
								$ulim = $ulim -1;
							} else {
							}
						} 
					}else{
					break;
					}
					}else{
						break;
					}
				}
				}else{
					echo "empty";
				}
			}else{
				echo "same";
			}
		} else {
		}

		$file = "./followers/".$tid.".csv";
		file_put_contents($file, $followers);
		if($org_ulim != $ulim){
			echo $ulim;
			$url = 'http://54.148.224.187/tools/php/update.php';
			$data = array(
				'tid' => $tid,
				'ulim' => $ulim,
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
			$resp_up = file_get_contents($url, false, stream_context_create($options));
			echo $resp_up;
		}
		
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
	echo $code;
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


function getDesc(){
	global $ord;
	global $list;
	global $count;
	global $tmhOAuth;
	global $tname;


	//$desc_arr = [1,0,7];
	$col0 = rand(0,11);
	$col1 = rand(0,11);
	while($col0 == $col1 ){
		$col1 = rand(0,11);
	}
	
	$col2 = rand(0,11);
	while($col0 == $col2 ||  $col1 == $col2){
		$col2 = rand(0,11);
	}
	
	$desc_arr = [$col0,$col1,$col2];

	$txt = "";

	for($i=0; $i<3; $i++){
		if($desc_arr[$i] == 0){
			$txt .= "I will never forget those tweets that you faved, !---!in my heart, those moments are engraved";
		}
		if($desc_arr[$i] == 1){
			$txt .= "May your feed be better without me,!---!but your memory of me still be";
		}
		if($desc_arr[$i] == 2){
			$favres = makeFav($list[$count]);
			$txt .= $favres;
		}
		if($desc_arr[$i] == 3){
			$txt .= "140 characters can’t express how I feel.!---!This poem shows you, that my feels are real";
		}
		if($desc_arr[$i] == 4){
			$flres = getCheckFollowOne($list[$count]);
			$txt .= $flres;
		}

		if($desc_arr[$i] == 5){
			$flres = getCheckFollowTwo($list[$count]);
			$txt .= $flres;
		}

		if($desc_arr[$i] == 6){
			$rtres = makeRetweet($list[$count]);
			$txt .= $rtres;
		}
		if($desc_arr[$i] == 7){
			$txt .= "You choose to continue your feed without me,!---!but without you, my tweets feel so lonely";
		}
		if($desc_arr[$i] == 8){
			$txt .= "I promised myself I wouldn’t cry,!---!but for every favorite you gave, a tear flows out of my eye";
		}

		if($desc_arr[$i] == 9){
			$txt .= getFollowerText();
		}

		if($desc_arr[$i] == 10){
			$txt .= getOrderText();
		}

		if($desc_arr[$i] == 11){
			$txt .= "I dedicated my last tweet to you,!---!please see this gesture as my way to say adieu";
			$ran = rand(0,1);
			if($ran == 0){
				$body = "Today, @".$tname." choose to unfollow me, goodbye my friend, all alone, my tweets will be.";
			}else{
				$body = "This is a goodbye to @".$tname.", who unfollowed me today. follow me again, I promise to be better to you, someday.";
			}
			$code = $tmhOAuth->request('POST', 'https://api.twitter.com/1.1/statuses/update.json',
			array(
				'status' => $body
			),
			true,
			true
			);
			if ($tmhOAuth->response["code"] == 200){ 
				echo "success";
			} else {
				var_dump($tmhOAuth->response["code"]);
				echo $tmhOAuth->response["code"];
			}
		}
		if($i != 2){
			$txt .= "!---!!---!";
		}
	}


	return $txt;
}

function getCheckFollowOne(){
	
	global $following;
	if($following){
		$txt = "As my heart is full of sorrow,!---!I wonder of me too, I should click unfollow";
	}else{
		$txt = "I wish I followed you from the start,!---!I will let go forever, my tweets that tore us apart";
	}
	return $txt;
}

function getCheckFollowTwo(){
	
	global $tmhOAuth;
	global $following;
	global $tname;

	if($following){
		$txt = "As my heart is full of sorrow,!---!I wonder of me too, I should click unfollow";
	}else{
		$follow = $tmhOAuth->request('POST', 'https://api.twitter.com/1.1/friendships/create.json',
		array(
			'screen_name' => $tname,
			'follow' => true		
		)
		);
		if ($follow == 200) {
			$txt = "Maybe I should have followed you from the beginning,!---!I just followed you now, please be forgiving";
		}else{
			$txt = "Maybe I should have followed you from the beginning,!---!I just followed you now, please be forgiving";
		}
	}
	return $txt;
}


function getFollowerText(){
	
	global $frnum;
	$txt = "You follow ".$frnum." people from everywhere, !---!why is there for me, no place in there anymore";
	return $txt;
}


function getOrderText(){
	
	global $ord;
	$last = $ord % 10;
	$tail = "th";
	if($last == 1){
		$tail = "st";
	}else if($last == 2){
		$tail = "nd";
	}else if($last == 3){
		$tail = "rd";
	}
	$txt = "You read my tweets for so long,!---!you are my ".$ord.$tail." follower, I am heartbroken to see you go";
	return $txt;
}


function makeFav($mid){
	global $tmhOAuth;
	$fave = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/statuses/user_timeline.json',
		array(
			'user_id' => $mid,
			'count' => 1		
		)
	);
	//echo $fave;
	if ($fave == 200) {
		$jsp = json_decode($tmhOAuth->response['response']);
		if(count($jsp) >0){
			$twid = $jsp[0] ->{'id_str'};
			$fvc = $tmhOAuth->request('POST', 'https://api.twitter.com/1.1/favorites/create.json',
				array(
					'id' => $twid
				),
				true,
				true
			);
			if ($tmhOAuth->response["code"] == 200){ 
				//echo "fav_success";
			} else {
				//echo "fav_failed";
			}
		}
		return "I hope this goodbye is not forever,!---!I favorited your tweet, a last moment for you to remember";
	}else{
		return "I hope this goodbye is not forever,!---!I favorited your tweet, a last moment for you to remember";
	}
}

function makeRetweet($mid){
	global $tmhOAuth;
	$fave = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/statuses/user_timeline.json',
		array(
			'user_id' => $mid,
			'count' => 1		
		)
	);
	//echo $fave;
	if ($fave == 200) {
		$jsp = json_decode($tmhOAuth->response['response']);
		if(count($jsp) >0){
			$twid = $jsp[0] ->{'id_str'};
			$fvc = $tmhOAuth->request('POST', 'https://api.twitter.com/1.1/statuses/retweet/'.$twid.'.json',
				array(
				),
				true,
				true
			);
			if ($tmhOAuth->response["code"] == 200){ 
				//echo "rt_success";
			} else {
				//echo "rt_failed";
			}
		}
		return "I’m not sure what it is that I did wrong,!---!I retweeted your tweets, please don’t just move along";
	}else{
		return "I’m not sure what it is that I did wrong,!---!I retweeted your tweets, please don’t just move along";
	}
}

?>