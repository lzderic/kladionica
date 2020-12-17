<?php
    include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>

<?php

	$sql="SELECT COUNT(*) FROM momcad";
	$rs=izvrsiUpit($bp,$sql);
	$red=mysqli_fetch_row($rs);
	$broj_redaka=$red[0];
	$broj_stranica=ceil($broj_redaka/$vel_str_u);

	
	$sql="SELECT * FROM momcad ORDER BY momcad_id LIMIT ".$vel_str_u;
	if(isset($_GET['stranica'])){
		$sql=$sql." OFFSET ".(($_GET['stranica']-1)*$vel_str_u);
		$aktivna=$_GET['stranica'];
	}
	else $aktivna = 1;

	$rs=izvrsiUpit($bp,$sql);
	echo "<table>";
	echo "<caption>Popis momčadi</caption>";
	echo "<thead><tr>
		<th>Naziv</th>
        <th>Opis</th>
        <th></th>
		<th></th>";
	echo "</tr></thead>";
	
	echo "<tbody>";
	while(list($id,$liga_id,$naziv,$opis)=mysqli_fetch_row($rs)){
		echo "<tr>
			<td>$naziv</td>
			<td>$opis</td>";
			echo "<td><a class='link' href='momcad.php?momcad=$id&liga=$liga_id'>UREDI</a></td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";

	
	echo '<div id="paginacija">';
	
	if($aktivna!=1){
		$prethodna=$aktivna-1;
		echo "<a class='link' href=\"momcadi.php?stranica=".$prethodna."\">&lt;</a>";
	}
	for($i=1;$i<=$broj_stranica;$i++){
		echo "<a class='link";
		if($aktivna==$i)echo " aktivna"; 
		echo "' href=\"momcadi.php?stranica=".$i."\">$i</a>";
	}
	
	if($aktivna<$broj_stranica){
		$sljedeca=$aktivna+1;
		echo "<a class='link' href=\"momcadi.php?stranica=".$sljedeca."\">&gt;</a>";
	}
	echo "<br/>";
	echo '<a class="link" href="momcad.php">STVORI MOMČAD I DODAJ LIGI</a>';
	echo '</div>';
?>

<?php
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>