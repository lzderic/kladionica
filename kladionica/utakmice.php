<?php
    include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>

<?php

$sql="SELECT utakmica_id, moderator_id, momcad_1, momcad_2, datum_vrijeme_pocetka, datum_vrijeme_zavrsetka,rezultat_1,rezultat_2, m2.naziv AS naziv1, m3.naziv AS naziv2, utakmica.opis
FROM utakmica, momcad m2, momcad m3, liga l
WHERE utakmica.momcad_1 = m2.momcad_id AND utakmica.momcad_2 = m3.momcad_id AND l.liga_id = m2.liga_id 
and utakmica.rezultat_1 = -1 and utakmica.rezultat_2 = -1 and datum_vrijeme_zavrsetka > now()";
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
	echo "<caption>Popis utakmica koje nisu završile</caption>";
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
		if($aktivni_korisnik_tip<=2 && strtotime($datum_vrijeme_zavrsetka)>=strtotime(date("d.m.Y H:i:s"))){
			echo "<td><a href='stvoriListic.php?utakmica=$utakmica_id' class='link'>Stvori listić</a></td>";
		}
		
			if($aktivni_korisnik_tip==1){
				if($aktivni_korisnik_id==$moderator_id) {
					echo "<td>";
					if(strtotime($datum_vrijeme_pocetka)>strtotime(date("d.m.Y H:i:s"))){
					echo "<a href='utakmica.php?utakmica=$utakmica_id' class='link'>Uredi</a>";
					}
					

					echo "</td>";
				}
			}
			if($aktivni_korisnik_tip==0) {
				echo "<td>";
					echo "<a href='utakmica.php?utakmica=$utakmica_id' class='link'>Uredi</a> =>";
					if(strtotime($datum_vrijeme_zavrsetka)<strtotime(date("d.m.Y H:i:s")) && $rezultat1 == -1 && $rezultat2 == -1){
						echo " => <a href='utakmica.php?utakmicakraj=$utakmica_id' class='link'>Upiši rezultat</a>";
					}
					echo "</td>";
			}
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";

	
	echo '<div id="paginacija">';
	
	if($aktivna!=1){
		$prethodna=$aktivna-1;
		echo "<a class='link' href=\"utakmice.php?stranica=".$prethodna."\">&lt;</a>";
	}
	for($i=1;$i<=$broj_stranica;$i++){
		echo "<a class='link";
		if($aktivna==$i)echo " aktivna"; 
		echo "' href=\"utakmice.php?stranica=".$i."\">$i</a>";
	}
	
	if($aktivna<$broj_stranica){
		$sljedeca=$aktivna+1;
		echo "<a class='link' href=\"utakmice.php?stranica=".$sljedeca."\">&gt;</a>";
	}
	echo "<br/>";
	if($aktivni_korisnik_tip==0||$aktivni_korisnik_tip==1){
		echo '<a class="link" href="utakmica.php">DODAJ UTAKMICU</a><br>';
		echo '<a class="link" href="utakmiceZavrsene.php">Utakmice koje su završile - upis rezultata</a><br>';
		echo '<a class="link" href="utakmiceZavrseneSve.php">Sve utakmice koje su završile - pogled rezultata</a><br>';
	}
	
?>
</div>

<?php
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>