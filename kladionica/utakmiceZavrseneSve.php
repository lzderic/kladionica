<?php
    include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>

<?php

$sql="SELECT utakmica_id, moderator_id, momcad_1, momcad_2, datum_vrijeme_pocetka, datum_vrijeme_zavrsetka,rezultat_1,rezultat_2, m2.naziv AS naziv1, m3.naziv AS naziv2, utakmica.opis
FROM utakmica, momcad m2, momcad m3, liga l
WHERE utakmica.momcad_1 = m2.momcad_id AND utakmica.momcad_2 = m3.momcad_id AND l.liga_id = m2.liga_id 
and utakmica.rezultat_1 <> -1 and utakmica.rezultat_2 <> -1 and datum_vrijeme_zavrsetka < now()";
if($aktivni_korisnik_tip==1){
    $sql.=" and moderator_id = ".$aktivni_korisnik_id;
}
$rs=izvrsiUpit($bp,$sql);
$red=mysqli_fetch_row($rs);
$broj_redaka=mysqli_num_rows($rs);
$broj_stranica=ceil($broj_redaka/$vel_str);
$sql.=" LIMIT ".$vel_str;

if(isset($_GET['stranica'])){
	$sql=$sql." OFFSET ".(($_GET['stranica']-1)*$vel_str);
	$aktivna=$_GET['stranica'];
}
else $aktivna=1;

	$rs=izvrsiUpit($bp,$sql);
	echo "<table>";
	echo "<caption>Popis utakmica koje su završile - ispis rezultata</caption>";
    echo "<thead><tr>
		<th>Momčadi</th>
		<th>Rezultat</th>
		<th>Vrijeme početka</th>
		<th>Vrijeme završetka</th>
		<th>Opis</th>
		<th></th>
		<th></th>
		<th></th>";
	echo "</tr></thead>";	
	echo "<tbody>";
	while(list($utakmica_id, $moderator_id, $momcad_1,$momcad_2,$datum_vrijeme_pocetka,$datum_vrijeme_zavrsetka,$rezultat1,$rezultat2,$naziv1,$naziv2,$opis)=mysqli_fetch_row($rs)){
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
		echo "<a class='link' href=\"utakmiceZavrseneSve.php?stranica=".$prethodna."\">&lt;</a>";
	}
	for($i=1;$i<=$broj_stranica;$i++){
		echo "<a class='link";
		if($aktivna==$i)echo " aktivna"; 
		echo "' href=\"utakmiceZavrseneSve.php?stranica=".$i."\">$i</a>";
	}
	
	if($aktivna<$broj_stranica){
		$sljedeca=$aktivna+1;
		echo "<a class='link' href=\"utakmiceZavrseneSve.php?stranica=".$sljedeca."\">&gt;</a>";
	}
	echo "<br/>";
	
?>
<br><a href="javascript:history.back(-1)">Natrag</a>
</div>

<?php
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>