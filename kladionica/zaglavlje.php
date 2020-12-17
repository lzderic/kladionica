<?php
ob_start();
include("baza.php");
	if(session_id()=="")session_start();

	$trenutna=basename($_SERVER["PHP_SELF"]);
	$trenutna2=basename($_SERVER["PHP_SELF"]);
	$putanja=$_SERVER['REQUEST_URI'];
	$aktivni_korisnik=0;
	$aktivni_korisnik_tip=-1;
	$vel_str=5; 
	$vel_str_u=20; 	

	if(isset($_SESSION['aktivni_korisnik'])){
		$aktivni_korisnik=$_SESSION['aktivni_korisnik'];
		$aktivni_korisnik_ime=$_SESSION['aktivni_korisnik_ime'];
		$aktivni_korisnik_tip=$_SESSION['aktivni_korisnik_tip'];
		$aktivni_korisnik_id=$_SESSION["aktivni_korisnik_id"];
	}
?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Kladionica</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style.css">
		<script type="text/javascript" src="kladionica.js"></script>
		<script>
		function PokaziDruguMomcad(momcadid,aktivni_korisnik_id,utakmicaid) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("drugamomcad").innerHTML = this.responseText;
			}
			};
			xmlhttp.open("GET","vratidrugumomcad.php?momcad1="+momcadid+"&moderatorid="+aktivni_korisnik_id+"&utakmicaid="+utakmicaid,true);
			xmlhttp.send();
		}
		</script>
    </head>
    <body>
    <div>
		<header>
			<span>
				<strong>Izgradnja Web aplikacija - Kladionica</strong>
				<br/>
				<?php 
					echo "<strong>Trenutna lokacija: </strong>".$trenutna."<br/>";
					if($aktivni_korisnik===0){
						echo "<span><strong>Status: </strong>Neprijavljeni korisnik</span><br/>";
						echo "<a class='link' href='prijava.php'>prijava</a>";
					}
					else{
						echo "<span><strong>Status: </strong>Dobrodošli, $aktivni_korisnik_ime</span><br/>";
						echo "<a class='link' href='prijava.php?logout=1'>odjava</a>";
					}
				?>
			</span>
		</header>
		<nav id="navigacija" class="menu">
			<ul>
				<?php
					if($aktivni_korisnik_tip==0) {
						
						echo '<a href="index.php"';
						if($trenutna=="index.php")echo ' class="aktivna"';
						echo ">POČETNA</a> ";
						echo '<a href="korisnici.php"';
						if($trenutna=="korisnici.php")echo ' class="aktivna"';
						echo ">KORISNICI</a> ";
						echo '<a href="o_autoru.html"';
						if($trenutna=="o_autoru.html")echo ' class="aktivna"';
						echo ">AUTOR</a> ";
						echo '<a href="statistikaPoKorisniku.php"';
						if($trenutna=="statistikaPoKorisniku.php")echo ' class="aktivna"';
						echo ">STATISTIKA</a> ";
						echo '<a href="statistikaPoLigi.php"';
						if($trenutna=="statistikaPoLigi.php")echo ' class="aktivna"';
						echo ">STATISTIKA PO LIGI</a> ";
						echo '<a href="statusListica.php"';
						if($trenutna=="statusListica.php")echo ' class="aktivna"';
						echo ">STATUS LISTICA</a> ";
						echo '<a href="momcadi.php"';
						if($trenutna=="momcadi.php")echo ' class="aktivna"';
						echo ">MOMČADI</a> ";
						echo '<a href="utakmice.php"';
						if($trenutna=="utakmice.php")echo ' class="aktivna"';
						echo ">UTAKMICE</a> ";


					}elseif($aktivni_korisnik_tip==1) {
									
						echo '<a href="index.php"';
						if($trenutna=="index.php")echo ' class="aktivna"';
						echo ">POČETNA</a> ";
						echo '<a href="o_autoru.html"';
						if($trenutna=="o_autoru.html")echo ' class="aktivna"';
						echo ">AUTOR</a> ";
						echo '<a href="statistikaPoKorisniku.php"';
						if($trenutna=="statistikaPoKorisniku.php")echo ' class="aktivna"';
						echo ">STATISTIKA</a> ";
						echo '<a href="statistikaPoLigi.php"';
						if($trenutna=="statistikaPoLigi.php")echo ' class="aktivna"';
						echo ">STATISTIKA PO LIGI</a> ";
						echo '<a href="statusListica.php"';
						if($trenutna=="statusListica.php")echo ' class="aktivna"';
						echo ">STATUS LISTICA</a> ";
						echo '<a href="utakmice.php"';
						if($trenutna=="utakmice.php")echo ' class="aktivna"';
						echo ">UTAKMICE</a> ";					
					
					}elseif($aktivni_korisnik_tip==2) {

								
						echo '<a href="index.php"';
						if($trenutna=="index.php")echo ' class="aktivna"';
						echo ">POČETNA</a> ";
						echo '<a href="o_autoru.html"';
						if($trenutna=="o_autoru.html")echo ' class="aktivna"';
						echo ">AUTOR</a> ";
						echo '<a href="statusListica.php"';
						if($trenutna=="statusListica.php")echo ' class="aktivna"';
						echo ">STATUS LISTICA</a> ";
						echo '<a href="utakmice.php"';
						if($trenutna=="utakmice.php")echo ' class="aktivna"';
						echo ">UTAKMICE</a> ";			
							
					}elseif($aktivni_korisnik_tip==-1){				

						echo ' <a href="index.php"';
						if($trenutna=="index.php")echo ' class="aktivna"';
						echo ">POČETNA</a> ";
						echo '<a href="o_autoru.html"';
						if($trenutna=="o_autoru.html")echo ' class="aktivna"';
						echo ">AUTOR</a> ";									
					}		
					
				?>
			</ul>
        </nav>
            </div>
		<section id="sadrzaj">
    </body>
</html>