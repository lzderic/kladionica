<?php
	include("zaglavlje.php");
	$bp=spojiSeNaBazu();

	if(isset($_GET["listicid"])){
		$idlistic=$_GET["listicid"];
		$sql="update listic set status = 'P' where listic_id =".$idlistic;
		$rs=izvrsiUpit($bp,$sql);
		header("Location: statusListica.php");
	}

	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>
