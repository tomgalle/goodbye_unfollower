<?php
	$id = $_POST['num'];
	$file = file_get_contents('./rep.txt', true);

	$path = "./rep.txt";

	$string = $file.$id."\n";
	file_put_contents($path, $string) ;


?>