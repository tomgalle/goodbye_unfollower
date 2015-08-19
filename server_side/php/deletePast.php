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


		if(file_exists ( '/var/www/html/tools/php/lasts/'.$tid.'.csv')){
			unlink('/var/www/html/tools/php/lasts/'.$tid.'.csv');
		}
}

?>