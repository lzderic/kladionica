<?php
    include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>

<?php


if(isset($_GET['id'])){
	$id = $_GET['id'];



$sql="SELECT utakmica_id, momcad_1, momcad_2, datum_vrijeme_pocetka, datum_vrijeme_zavrsetka,rezultat_1,rezultat_2, m2.naziv AS naziv1, m3.naziv AS naziv2, utakmica.opis
FROM utakmica
JOIN momcad m2 ON utakmica.momcad_1 = m2.momcad_id
JOIN momcad m3 ON utakmica.momcad_2 = m3.momcad_id
WHERE m2.liga_id = '$id'";

$rs=izvrsiUpit($bp,$sql);
$red=mysqli_fetch_row($rs);
$broj_redaka=mysqli_num_rows($rs);
$broj_stranica=ceil($broj_redaka/$vel_str);

$sql.=" LIMIT $vel_str";
$rs=izvrsiUpit($bp,$sql);
}
if(isset($_GET['stranica'])){
	$sql=$sql." OFFSET ".(($_GET['stranica']-1)*$vel_str);
	$aktivna=$_GET['stranica'];
}
else $aktivna=1;



	$rs=izvrsiUpit($bp,$sql);
	echo "<table>";
	echo "<caption>Popis utakmica koje su završile</caption>";
    echo "<thead><tr>
		<th>Momčadi</th>
		<th>Rezultat</th>
		<th>Vrijeme početka</th>
		<th>Vrijeme završetka</th>
		<th>Opis</th>
		<th></th>
		<th></th>";

	echo "</tr></thead>";
	
	echo "<tbody>";
	while(list($id,$momcad_1,$momcad_2,$datum_vrijeme_pocetka,$datum_vrijeme_zavrsetka,$rezultat1,$rezultat2,$naziv1,$naziv2,$opis)=mysqli_fetch_row($rs)){
		$datum_vrijeme_pocetka = date("d.m.Y H:i:s",strtotime($datum_vrijeme_pocetka));
		$datum_vrijeme_zavrsetka = date("d.m.Y H:i:s",strtotime($datum_vrijeme_zavrsetka));
		echo "<tr>";
		echo"<td>$naziv1 - $naziv2</td>";
		echo"<td>$rezultat1 - $rezultat2</td>";
		echo"<td>$datum_vrijeme_pocetka</td>";
		echo"<td>$datum_vrijeme_zavrsetka</td>";
		echo"<td>$opis</td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";

	
	echo '<div id="paginacija">';

	if($aktivna!=1){
		$prethodna=$aktivna-1;
		echo "<a class='link' href=\"utakmiceKojeSuZ.php?";
		if(isset($_GET["id"])){
			$id=$_GET["id"];
			echo "id=$id&";
		}		
		echo "stranica=".$prethodna."\">&lt;</a>";
	}
	for($i=1;$i<=$broj_stranica;$i++){
		echo "<a class='link";
		if($aktivna==$i)echo " aktivna"; 
		echo "' href=\"utakmiceKojeSuZ.php?";
		if(isset($_GET["id"])){
			$id=$_GET["id"];
			echo "id=$id&";
		}	
		echo "stranica=".$i."\">$i</a>";
	}
	
	if($aktivna<$broj_stranica){
		$sljedeca=$aktivna+1;
		echo "<a class='link' href=\"utakmiceKojeSuZ.php?";
		if(isset($_GET["id"])){
			$id=$_GET["id"];
			echo "id=$id&";
		}	
		echo "stranica=".$sljedeca."\">&gt;</a>";
	}
	
	
?>

<?php
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>