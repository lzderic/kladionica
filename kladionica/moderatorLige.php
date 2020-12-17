<?php
	include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>
<?php
	
	$sql="SELECT COUNT(*) FROM liga where moderator_id=$aktivni_korisnik_id";
	$rs=izvrsiUpit($bp,$sql);
	$red=mysqli_fetch_row($rs);
	$broj_redaka=$red[0];
	$broj_stranica=ceil($broj_redaka/$vel_str_u);

	
	$sql="SELECT * FROM liga where moderator_id=$aktivni_korisnik_id ORDER BY liga_id LIMIT ".$vel_str_u;
	if(isset($_GET['stranica'])){
		$sql=$sql." OFFSET ".(($_GET['stranica']-1)*$vel_str_u);
		$aktivna=$_GET['stranica'];
	}
	else $aktivna=1;
	$rs=izvrsiUpit($bp,$sql);

	echo "<table>";
		echo "<caption>Popis liga gdje sam moderator:</caption>";
		echo "<thead><tr>
		<th>Naziv lige</th>
		<th>Slika</th>
		<th>Opis</th>
		<th>Video</th>
		<th>Završene utakmice</th>
		<th></th>
	</tr></thead>";
	
	echo "<tbody>";
	while(list($id,$moderator_id,$naziv,$slika,$video,$opis)=mysqli_fetch_row($rs)){
		echo "<tr>
			<td>$naziv</td>
			<td><figure><img src='$slika' width='70' height='100' alt='Slika lige $naziv'/></figure></td>
			<td>$opis</td>";
			echo "<td>";
			if($video!=""){
			echo "<a href=$video class='link'>LINK </a>";
			}
			else{
				echo "Videozapis nije evidentiran!";
			}		
			echo "</td>";
			echo "<td><a href='utakmiceKojeSuZ.php?id=$id' class='link'>UTAKMICE</a></td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";

	
	echo '<div id="paginacija">';
	
	if ($aktivna!=1){
		$prethodna=$aktivna-1;
		echo "<a class='link' href=\"moderatorLige.php?stranica=".$prethodna."\">&lt;</a>";
	}
	for($i=1;$i<=$broj_stranica;$i++){
		echo "<a class='link";
		if($aktivna==$i)echo " aktivna"; 
		echo "' href=\"moderatorLige.php?stranica=".$i."\">$i</a>";
	}
	
	if($aktivna<$broj_stranica){
		$sljedeca=$aktivna+1;
		echo "<a class='link' href=\"moderatorLige.php?stranica=".$sljedeca."\">&gt;</a>";
	}
	echo '<br/>';
	if($aktivni_korisnik_tip==0)echo '<a class="link" href="liga.php">DODAJ LIGU</a>';
	echo '</div>';
?>
<?php
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>
