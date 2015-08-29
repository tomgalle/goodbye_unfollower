<?php
	$baseDate = 0;
	$target;
	if($dir = opendir("/var/www/html/tools/php/log/")){
		while (($file = readdir($dir)) !== false){
			if($file != "." && $file != ".."){
				$updateDate = filemtime("/var/www/html/tools/php/log/".$file);
				if($updateDate > $baseDate){
					$baseDate = $updateDate;
					$target = "/var/www/html/tools/php/log/".$file;
				}
			}
		}
		closedir($dir);
		$log = file_get_contents($target);
		$fl = count(explode("failed", $log))-1;
		$sc = count(explode("success", $log))-1;
		$sm = count(explode("same", $log))-1;
		$nm = count(explode("msend", $log))-1;
		echo "Error Count: ".$fl ." / Success Count: ".$sc ." / No Change Count: ".$sm ." / Participant Count: ".$nm;

		if($fl > 0){
			$con = "[Error! ".$fl."] ";
		}else{
			$con = "[OK] ";
		}

		$to = "qanta@qanta.jp";
		$to2 = "galle.tom@gmail.com";
		$subject = $con."Goodbye Unfollower Report";
		$body = "Error Count: ".$fl ." / Success Count: ".$sc ." / No Change Count: ".$sm ." / Participant Count: ".$nm;
		$from = "qanta@prty.jp";
 
		echo mail($to,$subject,$body,"From:".$from);
		echo mail($to2,$subject,$body,"From:".$from);
	}
?>