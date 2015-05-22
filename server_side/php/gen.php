<?php
	$tname = $_POST['nm'];
	$order = $_POST['order'];
	$uid = uniqid();
	
	shell_exec('mkdir /var/www/html/tools/tmp/'.$uid);
	shell_exec("convert  -transparent white -fill black  -font /var/www/html/tools/img_gen/tnrb.ttf -pointsize 18.15 -size 384x  -antialias caption:'Farewell ".$tname."' /var/www/html/tools/tmp/".$uid."/ttl.png");
	shell_exec("convert  -transparent white -fill black  -font /var/www/html/tools/img_gen/tnr.ttf -pointsize 18.15 -size 384x  -antialias caption:'You were my ".$order."th follower at the moment you unfollowed me,\nThat is why I am so sad to see you go\n\nI will never forget those 20000 tweets that you faved,\nin my heart, those moments are engraved\n\nmay your feed be better without me,\nbut your memory of me still be' /var/www/html/tools/tmp/".$uid."/cap.png");
	shell_exec("convert /var/www/html/tools/img_gen/bg.png /var/www/html/tools/tmp/".$uid."/cap.png -compose multiply -gravity northwest -geometry +32+135 -composite /var/www/html/tools/twimg/".$uid.".png");
	shell_exec("convert /var/www/html/tools/twimg/".$uid.".png /var/www/html/tools/tmp/".$uid."/ttl.png -compose multiply -gravity northwest -geometry +32+92 -composite /var/www/html/tools/twimg/".$uid.".png");
	echo $uid;
?>