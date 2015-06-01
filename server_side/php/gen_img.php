<?php
	$tname = $_POST['nm'];
	$uid = uniqid();
	$txt = $_POST['txt'];
	$txt = str_replace(array("!---!"), "\n", $txt);

	$ran = rand(0,7);
	$col = "white";
	$bg = "black";
	$bl = "screen";
	if($ran == 4 || $ran == 7){
		$col = "black";
		$bg = "white";
		$bl = "multiply";
	}
	shell_exec('mkdir /var/www/html/tools/tmp/'.$uid);
	file_put_contents("/var/www/html/tools/tmp/".$uid."/message.txt", $txt);
	shell_exec("convert -background ".$bg."  -transparent ".$bg." -fill ".$col."  -font /var/www/html/tools/img_gen/tnrb.ttf -pointsize 44.95 -size 702x  -antialias caption:'Farewell ".$tname."' /var/www/html/tools/tmp/".$uid."/ttl.png");
	shell_exec("convert -background ".$bg." -transparent ".$bg." -fill ".$col."  -font /var/www/html/tools/img_gen/tnr.ttf -pointsize 34.58 -size 702x  -antialias caption:@/var/www/html/tools/tmp/".$uid."/message.txt /var/www/html/tools/tmp/".$uid."/cap.png");
	
	$imginfo = getimagesize("/var/www/html/tools/tmp/".$uid."/cap.png");
	
	$cp = (900-($imginfo[1]+93))/2+93;
	$pd = (900-($imginfo[1]+93))/2;

	shell_exec("convert /var/www/html/tools/img_gen/bg".$ran.".png /var/www/html/tools/tmp/".$uid."/cap.png -compose ".$bl." -gravity northwest -geometry +197+".$cp." -composite /var/www/html/tools/twimg/".$uid.".png");
	shell_exec("convert /var/www/html/tools/twimg/".$uid.".png /var/www/html/tools/tmp/".$uid."/ttl.png -compose ".$bl." -gravity northwest -geometry +197+".$pd." -composite /var/www/html/tools/twimg/".$uid.".png");
	shell_exec('rm -r /var/www/html/tools/tmp/'.$uid);
	echo $uid;
	
?>