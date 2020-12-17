<?php
	include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>
<?php
	if(!isset($_SESSION['aktivni_korisnik']))header("Location:index.php");
	if($aktivni_korisnik_tip==0||$aktivni_korisnik_tip==1){
		echo '<div id="opis">
			<h2>Napomena</h2>
			<p>Datumi se unose u formatu dd.mm.yyyy., npr. 10.10.2019.,
			za pretragu po ligi unesite jedno ili više slova iz imena lige</p>
			<br/>
		</div>';
		echo '<form method="GET" action="statistikaPoLigi.php">
			<table><caption>FILTER</caption><tbody>
			<tr>';
		echo '<td><label for="liga">Liga:</label>';
		echo '<input type="text" value="';if(isset($_GET['liga'])&&!isset($_GET['reset']))echo $_GET['liga'];
		echo '" name="liga" id="liga"/></td>';
		echo '<td><label for="od">Od datuma:</label>';
		echo '<input type="text" value="';if(isset($_GET['od'])&&!isset($_GET['reset']))echo $_GET['od'];
		echo '" name="od" id="od" size="20"/></td>
			<td><label for="do">Do datuma:</label>';
		echo '<input type="text" value="';if(isset($_GET['do'])&&!isset($_GET['reset']))echo $_GET['do'];
		echo '" name="do" id="do" size="20" onclick="postaviDatum(this)"/></td>
			<td><input type="submit" name="reset" value="Izbriši"/></td>
			<td><input type="submit" name="submit" value="Filter"/></td>
			</tr></tbody>
			</table></form>
		';
	}

	if(isset($_GET["liga"]) || isset($_GET["od"]) || isset($_GET["do"])){

		if(empty($_GET["od"])){
			$mindatumod="select min(datum_vrijeme_pocetka) as 'MinDatum' from utakmica";
			$ex = izvrsiUpit($bp,$mindatumod);
			$row=mysqli_fetch_array($ex);
			$_GET["od"]=date("d.m.Y H:i:s",strtotime($row["MinDatum"]));
		}

		if(empty($_GET["do"])){
			$maxdatumdo="select max(datum_vrijeme_pocetka) as 'MaxDatum' from utakmica";
			$ex = izvrsiUpit($bp,$maxdatumdo);
			$row=mysqli_fetch_array($ex);
			$_GET["do"]=date("d.m.Y H:i:s",strtotime($row["MaxDatum"]));
		}

		$trenutna.="?liga=".$_GET["liga"]."&od=".$_GET["od"]."&do=".$_GET["do"]."&";
		$trenutna2.="?liga=".$_GET["liga"]."&od=".$_GET["od"]."&do=".$_GET["do"]."&";
		
	}
	else{
		$trenutna.="?";
		$trenutna2.="?";
	}
	
	$sql="SELECT * FROM (
		SELECT
		lg.liga_id,
		lg.naziv,
		sum(case when l.`status` = 'D' then 1 ELSE 0 end) AS 'dobitni',
		sum(case when l.`status` = 'N' then 1 ELSE 0 end) AS 'nedobitni'
		FROM listic l
		INNER JOIN korisnik k
		ON l.korisnik_id = k.korisnik_id
		INNER JOIN utakmica u
		ON u.utakmica_id = l.utakmica_id
		INNER JOIN momcad m1
		ON u.momcad_1 = m1.momcad_id
		INNER JOIN momcad m2
		ON u.momcad_2 = m2.momcad_id
		INNER JOIN liga lg
		ON lg.liga_id = m1.liga_id AND lg.liga_id = m2.liga_id
		WHERE l.`status` IN ('D','N')";
		if(isset($_GET["od"]) && isset($_GET["do"])){
			$datumod=date("Y-m-d H:i:s",strtotime($_GET["od"]));
			$datumdo=date("Y-m-d H:i:s",strtotime($_GET["do"]));
			$sql.=" AND u.datum_vrijeme_pocetka >= '$datumod' AND u.datum_vrijeme_zavrsetka <= '$datumdo'";
		}
		if(isset($_GET["liga"])){
			$nazivliga=$_GET["liga"];
			$sql.=" AND lg.naziv LIKE '%$nazivliga%'";
		}
		if($aktivni_korisnik_tip==1){
			$sql.=" AND lg.moderator_id = ".$aktivni_korisnik_id;
		}
		
		$sql.=" GROUP BY lg.liga_id) AS nova";



		if(isset($_GET["dsort"])){

			if($_GET["dsort"]=="desc"){
				$sql.=" ORDER BY nova.dobitni desc";
				$trenutna.="dsort=asc";
			}
			else
			{
				$sql.=" ORDER BY nova.dobitni asc";
				$trenutna.="dsort=desc";
			}
		}
		else{
			$trenutna.="dsort=desc";
		}


		if(isset($_GET["nsort"])){

			if($_GET["nsort"]=="desc"){
				$sql.=" ORDER BY nova.nedobitni desc";
				$trenutna2.="nsort=asc";
			}
			else
			{
				$sql.=" ORDER BY nova.nedobitni asc";
				$trenutna2.="nsort=desc";
			}
		}
		else{
			$trenutna2.="nsort=desc";
		}



	$rs=izvrsiUpit($bp,$sql);
	echo "<table>";
	echo "<caption>Statistika po ligi</caption>";
	echo "<thead><tr>";
	echo "<th>Liga</th>";
	echo "<th><a href='".$trenutna."' class='sortable'>Dobitni <img src='images/strelica.png' alt='strelica' style='border:0;' title='Sortiraj po dobitnom'/></a></th>";
	echo "<th><a href='".$trenutna2."' class='sortable'>Ne dobitni <img src='images/strelica.png' alt='strelica' style='border:0;' title='Sortiraj po ne dobitnom'/></a></th>";
	echo "</tr></thead>";
	date_default_timezone_set("Europe/Zagreb");
	
	echo "<tbody>";
	while(list($liga_id,$naziv,$dobitni,$nedobitni)=mysqli_fetch_row($rs)){
		echo "<tr>
			<td>$naziv</td>
			<td>$dobitni</td>
			<td>$nedobitni</td>
		</tr>";
	}
	echo "</tbody>";
	echo "</table>";
?>
<?php
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>
