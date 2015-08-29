<?php
require_once("/var/www/html/tools/php/twitteroauth.php");
require '/var/www/html/tools/php/tmhOAuth.php';
require '/var/www/html/tools/php/tmhUtilities.php';

$log = "";
$inittime = rand();

echo $inittime;

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
$consumer_key = "Z4megDutleofnn9exjcWrDJDj";
// Consumer secretの値
$consumer_secret = "vVEjTOACVwRVLlAQ2PQcGhVB2VHDO3mw1BIrPAglRVIBzG2vI7";
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
		$prev = file_get_contents('/var/www/html/tools/php/followers/'.$tid.'.csv');
		$list = explode(",", $prev);

		$prevlist = array("0");

		if(file_exists ( '/var/www/html/tools/php/lasts/'.$tid.'.csv')){
			$prevunf = file_get_contents('/var/www/html/tools/php/lasts/'.$tid.'.csv');
			$prevlist = explode(",", $prevunf);
		}


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
							$log .= $list[$count];
							//echo $list[$count];
				
							$doubles = 0;
							echo "ddd".count($prevlist)."sss";
							for($j = 0; $j<count($prevlist); $j++){
								if($prevlist[$j] == $list[$count]){
									$doubles = 1;
									echo "doubleExec!";
								}
							}

							if($doubles == 0){
							
							array_push($prevlist, $list[$count]);
							$user = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/users/show.json',
							array(
								'user_id' => $list[$count]
							)
							);

							if ($user == 200) {
								$jsp = json_decode($tmhOAuth->response['response']);
								$tname = $jsp ->{'screen_name'};
								$frnum = $jsp ->{'friends_count'};
								$fwnum = $jsp ->{'followers_count'};
								$following = $jsp ->{'following'};
						
								
						
								$url = 'http://goodbye-301660524.us-west-2.elb.amazonaws.com/tools/php/gen_img.php';
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
								$log .= $contents;
								echo $contents;

								$image = "/var/www/html/tools/twimg/".$contents.".png";
								$body = "Goodbye @".$tname.", I wrote this for you:";

								$code = $tmhOAuth->request('POST', 'https://api.twitter.com/1.1/statuses/update_with_media.json',
								array(
									'media[]'  => file_get_contents($image),
									'status' => $body
								),
								true,
								true
								);
								if ($tmhOAuth->response["code"] == 200){ 
									
									$log .= "success";
									echo "success";
								} else {
									var_dump($tmhOAuth->response["raw"]);
									$log .= "failed";
									$log .= $tmhOAuth->response["code"];
									echo $tmhOAuth->response["code"];
							}
				
								$plim = $plim+1;
								$ulim = $ulim -1;
							} else {
							}
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
					$log .= "empty";
					echo "empty";
				}
			}else{
				$log .= "same";
				echo "same";
			}
		} else {
		}

		$file = "/var/www/html/tools/php/followers/".$tid.".csv";


		file_put_contents($file, $followers);

		

		if ( chmod($file,0777)) {
			echo "setP";
		} else {
			echo "failed";
		}

		$prevfile = "/var/www/html/tools/php/lasts/".$tid.".csv";
		$prevs = "";
		for($j = 0; $j<count($prevlist); $j++){
			$prevs .= $prevlist[$j];
			if($j != count($prevlist)-1){
				$prevs .= ",";
			}
		}

		file_put_contents($prevfile, $prevs);

		if ( chmod($prevfile,0777)) {
			
		} else {
		}

		if($org_ulim != $ulim){
			$log .= $ulim;
			echo $ulim;
			$url = 'http://goodbye-301660524.us-west-2.elb.amazonaws.com/tools/php/update.php';
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
			$log .= $resp_up;
			echo $resp_up;
		}
		
		$log .= "msend";
		echo "msend";

		
		$log .= "\n";

}



mysql_close($link);


$endtime = time();
$log .= $inittime;;
$log .= "\n";
$log .= $endtime;
$log .= "\n";
$span = $endtime-$inittime;
$log .= "\n";
$log .=$span;


file_put_contents("/var/www/html/tools/php/log/".$inittime.".txt", $log);

function getFollowers($cs){
	global $tmhOAuth;
	global $followers;
	global $tid;
	global $log;
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
		$log .= $tid;
		$log .= "<";
		$log .= $code;
		$log .= ">";
		$log .= "\n";
		if ($code == 401) {
			$log .= "failed";
			$link = mysql_connect("goodbye.ceyiw7ismype.us-west-2.rds.amazonaws.com:3306","qanta", "suta0220");
			if (!$link) {
				die('failed' . mysql_error());
			}
			$dbname = "goodbye";
			$tblname="users";

			mysql_select_db($dbname,$link);

			$res_result = mysql_query( "delete from $tblname where id = $tid;", $link);
		}
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
	global $log;


	//$desc_arr = [1,0,7];
	$col0 = rand(0,17);
	while($col0 == 9 || $col0 == 16|| $col0 == 2|| $col0 == 6){
		$col0 = rand(0,17);
	}
	$col1 = rand(0,17);
	

	while($col0 == $col1 ||$col1 == 9 || $col1 == 16||$col1 == 2 || $col1 == 6){
		$col1 = rand(0,17);
	}
	
	$col2 = rand(0,11);
	while($col0 == $col2 ||  $col1 == $col2 ||$col2 == 9 || $col2 == 16||$col2 == 2 || $col2 == 6){
		$col2 = rand(0,17);
	}
	
	$desc_arr = [$col0,$col1,$col2];

	$txt = "";

	for($i=0; $i<3; $i++){
		if($desc_arr[$i] == 0){
			$txt .= "140 characters can’t express how I feel,!---!this poem shows you, that my feels are real";
		}
		if($desc_arr[$i] == 1){
			$txt .= "I thought u and I, we were squad,!---!what u did right now destroyed that for good";
		}
		if($desc_arr[$i] == 2){
			$favres = makeFav($list[$count]);
			$txt .= $favres;
		}
		if($desc_arr[$i] == 3){
			$txt .= "I will never forget the tweets that you faved, !---!in my heart, those moments are engraved";
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
			$txt .= "ur unfollow leaves emptiness behind,!---!is it for my feels, that you are blind";
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
			$txt .= "a goodbye in dignity, I wrote for you,!---!the 140 characters I just tweeted to you, adieu";
			$ran = rand(0,1);
			if($ran == 0){
				$body = "Goodbye @".$tname.", I'm sad to see you go.";
			}else{
				$body = "Goodbye @".$tname.", I'm sad to see you go.";
			}
			$code = $tmhOAuth->request('POST', 'https://api.twitter.com/1.1/statuses/update.json',
			array(
				'status' => $body
			),
			true,
			true
			);
			if ($tmhOAuth->response["code"] == 200){ 
				$log .= "success";
				echo "success";
			} else {
				var_dump($tmhOAuth->response["code"]);
				$log .= $tmhOAuth->response["code"];
				echo $tmhOAuth->response["code"];
			}
		}

		if($desc_arr[$i] == 12){
			$txt .= "is it hate in your heart that drove u,!---!I only want followers with love, to come thru";
		}

		if($desc_arr[$i] == 13){
			$txt .= "idk how to digest losing you,!---!is it r kelly that I shall listen to, to get through";
		}
		if($desc_arr[$i] == 14){
			$txt .= "a tear just flew from my eye,!---!but rn I just want you out of my internet life";
		}

		if($desc_arr[$i] == 15){
			$txt .= getRecommendText();
		}
		if($desc_arr[$i] == 16){
			$txt .= getPopular();
		}
		if($desc_arr[$i] == 17){
			$txt .= getFollowNumText();
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
		$txt = "as my heart is full of sorrow,!---!I wonder of me too, I should click unfollow";
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
		$txt = "as my heart is full of sorrow,!---!I wonder of me too, I should click unfollow";
	}else{
		$follow = $tmhOAuth->request('POST', 'https://api.twitter.com/1.1/friendships/create.json',
		array(
			'screen_name' => $tname,
			'follow' => true		
		)
		);
		if ($follow == 200) {
			$txt = "maybe I should have followed you from the beginning, I just followed you now, please be forgiving";
		}else{
			$txt = "maybe I should have followed you from the beginning, I just followed you now, please be forgiving";
		}
	}
	return $txt;
}


function getRecommendText(){
	

	global $tmhOAuth;
	$sug = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/users/suggestions.json'
	);
	//echo $fave;
	$txt = "I knew we’re not bff’s, for sure,!---!is it these people, you like more?";
	if ($sug == 200) {
		$jsp = json_decode($tmhOAuth->response['response']);
		if(count($jsp) >0){
			$slug = $jsp[0] ->{'slug'};
			$sugmem = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/users/suggestions/'.$slug.'.json'
			);
			if ($tmhOAuth->response["code"] == 200){ 
				//echo "fav_success";
				$pres = json_decode($tmhOAuth->response['response']);
				if(count($pres -> {'users'}) >0){
					$plim = 3;
					$users = "";
					if(count($pres -> {'users'}) < 3){
						
						$plim = count($pres -> {'users'});
					}

					for($i = 0; $i<$plim; $i++){
						if($i != 0){
							$users .= "   ";
						}
						$users .= "@";
						$users .= $pres -> {'users'}[$i] -> {'screen_name'};
					}
					$txt .= "!---!!---!";
					$txt .= $users;
				}
			} else {
				//echo "fav_failed";
			}
		}
		return $txt;
	}else{
		return $txt;
	}
}


function getPopular(){
	global $tmhOAuth;
	global $tid;

	$btm = 0;
	$tweet = "";

	$pop = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/statuses/user_timeline.json',
		array(
			'user_id' => $tid	
		)

	);
	//echo $fave;
	$txt = "my tweets will fade in your memory,!---!remember this one, before i’m history";
	if ($pop == 200) {
		$jsp = json_decode($tmhOAuth->response['response']);
		if(count($jsp) >0){
			
			for($i = 0; $i<count($jsp); $i++){
				if($tweet ==""){
					$tweet = $jsp[$i] ->{'id_str'};
				}
				if($jsp[$i] ->{'retweet_count'}>$btm){
					$btm = $jsp[$i] ->{'retweet_count'};
					$tweet = $jsp[$i] ->{'id_str'};
				}
			}
			$sch = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/search/tweets.json',
				array(
					'since_id' => $tweet,
					'max_id' => $tweet	
				)
			);
			
			if ($sch == 200) {
				
				$sres = json_decode($tmhOAuth->response['response']);
				$add = $sres ->{'statuses'}[0] -> {'metadata'} -> {'iso_language_code'};
				
					$txt .= "!---!!---!";
					$txt .= $add;
			}else{
				$txt = "a tear just flew from my eye,!---!but rn I just want you out of my internet life";
			}
			
		}
		return $txt;
	}else{
		$txt = "a tear just flew from my eye,!---!but rn I just want you out of my internet life";
		return $txt;
	}
}




function getFollowNumText(){
	
	global $fwnum;
	$txt = "your timeline was on fire with my tweets,!---!rn it's up to you, and your ".$fwnum." followers, to substitute";
	return $txt;
}


function getFollowerText(){
	
	global $frnum;
	$txt = "you follow ".$frnum." people from everywhere, !---!why is there for me, no place in there anymore";
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
		return "where is it, that we went wrong?!---!I rt’d your last tweet, don’t just move along";
	}else{
		return "where is it, that we went wrong?!---!I rt’d your last tweet, don’t just move along";
	}
}




?>