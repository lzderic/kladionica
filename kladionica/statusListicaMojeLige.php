<?php
    include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>

<?php

	$sql="SELECT
	m1.naziv AS 'Momčad1',
	m2.naziv AS 'Momčad2',
	u.rezultat_1,
	u.rezultat_2,
	l.ocekivani_rezultat,
	u.datum_vrijeme_pocetka,
	l.`status`,
	CONCAT(k.ime,' ',k.prezime)
	FROM listic l
	INNER JOIN utakmica u
	ON l.utakmica_id = u.utakmica_id
	INNER JOIN momcad m1
	ON u.momcad_1 = m1.momcad_id
	INNER JOIN momcad m2
	ON u.momcad_2 = m2.momcad_id
	INNER JOIN liga lg
	ON m1.liga_id = lg.liga_id AND m2.liga_id = lg.liga_id
	INNER JOIN korisnik k
	ON l.korisnik_id = k.korisnik_id
	WHERE lg.moderator_id = ".$aktivni_korisnik_id;
    	$rs=izvrsiUpit($bp,$sql);
		$red=mysqli_fetch_row($rs);	

	$rs=izvrsiUpit($bp,$sql);
	echo "<table>";
	echo "<caption>Status listića</caption>";
	echo "<thead><tr>
		<th>Momčadi</th>
		<th>Rezultat</th>
		<th>0cekivani rezultat</th>
		<th>Datum početka</th>
		<th>Status</th>
		<th>Korisnik</th>";
	echo "</tr></thead>";
	
	echo "<tbody>";
	while(list($naziv1,$naziv2,$rezultat_1, $rezultat_2,$ocekivani_rezultat, $datum_vrijeme_pocetka,$status,$korisnik)=mysqli_fetch_row($rs)){
		$datum_vrijeme_pocetka = date("d.m.Y H:i:s",strtotime($datum_vrijeme_pocetka));

		echo "<tr>
			<td>$naziv1 - $naziv2</td>
			<td>$rezultat_1 - $rezultat_2</td>
			<td>$ocekivani_rezultat </td>
			<td>$datum_vrijeme_pocetka</td>
			<td>$status</td>
			<td>$korisnik</td>
			";
			
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";

	
?>


<?php
if($aktivni_korisnik_tip==1){
	echo "<p><a href='statusListica.php'>Status mojih listića</a></p>";
}
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>