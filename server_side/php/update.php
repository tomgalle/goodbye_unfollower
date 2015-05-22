<?php

$link = mysql_connect("goodbye.ceyiw7ismype.us-west-2.rds.amazonaws.com:3306","qanta", "suta0220");
if (!$link) {
    die('failed' . mysql_error());
}
$dbname = "goodbye";
$tblname="users";

$tid = $_POST['tid'];
$ulim = $_POST['ulim'];

mysql_select_db($dbname,$link);
if($ulim >0){
	mysql_query("UPDATE `users` SET `lim`='$ulim' WHERE `id`='$tid'");
	echo "decrease";
}else{
	mysql_query("DELETE FROM `users` WHERE `id`='$tid'");
	echo "remove!";
}



?>