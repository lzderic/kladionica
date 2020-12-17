<?php
    include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>

<?php

	$sql="SELECT l.listic_id, l.korisnik_id, m2.naziv AS naziv1, m3.naziv AS naziv2, rezultat_1, rezultat_2, ocekivani_rezultat, utakmica.datum_vrijeme_pocetka AS od, l.status
	FROM  listic l, utakmica, momcad m2, momcad m3, liga g, korisnik k
	WHERE l.utakmica_id=utakmica.utakmica_id AND utakmica.momcad_1 = m2.momcad_id AND utakmica.momcad_2 = m3.momcad_id AND g.liga_id=m2.liga_id AND g.liga_id=m3.liga_id AND g.moderator_id=k.korisnik_id
	AND l.korisnik_id = $aktivni_korisnik_id";
    	$rs=izvrsiUpit($bp,$sql);
		$red=mysqli_fetch_row($rs);
		$broj_redaka=mysqli_num_rows($rs);
		$broj_stranica=ceil($broj_redaka/$vel_str);
		$sql.=" LIMIT ".$vel_str_u;
		$rs=izvrsiUpit($bp,$sql);
	if(isset($_GET['stranica'])){
		$sql=$sql." OFFSET ".(($_GET['stranica']-1)*$vel_str);
		$aktivna=$_GET['stranica'];
	}
	else $aktivna = 1;

	$rs=izvrsiUpit($bp,$sql);
	echo "<table>";
	echo "<caption>Status listića</caption>";
	echo "<thead><tr>
		<th>Momčadi</th>
		<th>Rezultat</th>
		<th>0cekivani rezultat</th>
		<th>Datum početka</th>
		<th>Status</th>";
	echo "</tr></thead>";
	
	echo "<tbody>";
	while(list( $listic_id,$korisnik_id, $naziv1 ,$naziv2,$rezultat_1, $rezultat_2,$ocekivani_rezultat, $datum_vrijeme_pocetka,$status)=mysqli_fetch_row($rs)){
		$datum_vrijeme_pocetka = date("d.m.Y H:i:s",strtotime($datum_vrijeme_pocetka));
		if($status=="O"){
			$predaja=" - <a href='predajListic.php?listicid=$listic_id&predaj=1'>Predaj listić</a>";
			$azuriraj=" - <a href='stvoriListic.php?listicid=$listic_id&azuriraj=1'>Ažuriraj listić</a>";
		}
		else{
			$predaja="";
			$azuriraj="";
		}
		echo "<tr>
			<td>$naziv1 - $naziv2</td>
			<td>$rezultat_1 - $rezultat_2</td>
			<td>$ocekivani_rezultat </td>
			<td>$datum_vrijeme_pocetka</td>
			<td>$status $predaja $azuriraj</td>";
			
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";

	
	echo '<div id="paginacija">';
	
	if($aktivna!=1){
		$prethodna=$aktivna-1;
		echo "<a class='link' href=\"statusListica.php?stranica=".$prethodna."\">&lt;</a>";
	}
	for($i=1;$i<=$broj_stranica;$i++){
		echo "<a class='link";
		if($aktivna==$i)echo " aktivna"; 
		echo "' href=\"statusListica.php?stranica=".$i."\">$i</a>";
	}
	
	if($aktivna<$broj_stranica){
		$sljedeca=$aktivna+1;
		echo "<a class='link' href=\"statusListica.php?stranica=".$sljedeca."\">&gt;</a>";
	}
?>
</div>

<?php
if($aktivni_korisnik_tip==1){
	echo "<p><a href='statusListicaMojeLige.php'>Status ostalih listića moje lige</a></p>";
}
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>