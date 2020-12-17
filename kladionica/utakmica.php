<?php
	include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>
<?php
	$greska="";
	if(isset($_POST['submit'])){
		foreach ($_POST as $key => $value)if(strlen($value)==0)$greska="Sva polja za unos su obavezna";
		if(empty($greska)){
			$id=$_POST['novi'];
			$momcad_1=$_POST['momcad1'];
			$momcad_2=$_POST['momcad2'];
			$datum_vrijeme_pocetka=$_POST['datum_vrijeme_pocetka'];
			$datum_vrijeme_pocetka= date("Y-m-d H:i:s", strtotime($datum_vrijeme_pocetka));
			$datum_vrijeme_zavrsetka= date("Y-m-d H:i:s", strtotime('+90 minutes', strtotime($datum_vrijeme_pocetka)));
			if($id>0){
				$rezultat_1=$_POST['rezultat_1'];
				$rezultat_2=$_POST['rezultat_2'];
			}
			$opis=$_POST['opis'];

			

			if($id==0){
				$sql="INSERT INTO utakmica
				(momcad_1,momcad_2,datum_vrijeme_pocetka,datum_vrijeme_zavrsetka,rezultat_1,rezultat_2,opis)
				VALUES
				($momcad_1,'$momcad_2','$datum_vrijeme_pocetka','$datum_vrijeme_zavrsetka',-1,-1,'$opis');
				";
			}
			else{
				$sql="UPDATE utakmica SET
					momcad_1='$momcad_1',
					momcad_2='$momcad_2',
					datum_vrijeme_pocetka='$datum_vrijeme_pocetka',
					datum_vrijeme_zavrsetka='$datum_vrijeme_zavrsetka',
					rezultat_1='$rezultat_1',
					rezultat_2='$rezultat_2',
					opis='$opis'
					WHERE utakmica_id='$id'
				";
			}

			izvrsiUpit($bp,$sql);
			header("Location:utakmice.php");
		}
	}
	if(isset($_GET['utakmica']) || $_SERVER["QUERY_STRING"]==""){

	if(isset($_GET['utakmica'])){
		$id=$_GET['utakmica'];
		$sql="SELECT utakmica_id,  momcad_1, momcad_2, datum_vrijeme_pocetka, datum_vrijeme_zavrsetka,rezultat_1,rezultat_2, m2.naziv AS naziv1, m3.naziv AS naziv2, utakmica.opis
		FROM utakmica, momcad m2, momcad m3, liga l
		WHERE utakmica.momcad_1 = m2.momcad_id AND utakmica.momcad_2 = m3.momcad_id AND l.liga_id = m2.liga_id  AND utakmica_id='$id'";
		$rs=izvrsiUpit($bp,$sql);
		list($id,$momcad_1,$momcad_2,$datum_vrijeme_pocetka,$datum_vrijeme_zavrsetka,$rezultat_1,$rezultat_2,$naziv_1,$naziv_2,$opis)=mysqli_fetch_row($rs);
		$datum_vrijeme_pocetka= date("d.m.Y H:i:s", strtotime($datum_vrijeme_pocetka));
	}
	else{
		$id=0;
		$momcad_1="";
		$momcad_2="";
		$datum_vrijeme_pocetka="";
		$datum_vrijeme_zavrsetka="";
		$rezultat_1="";
		$rezultat_2="";
		$naziv_1="";
		$naziv_2="";
		$opis="";
	}
	if(isset($_POST['reset']))header("Location:urediUtakmicu.php");
?>
<form method="POST" action="utakmica.php">
	<table>
		<caption>
			<?php
				if($id>0){
					echo "UREDI UTAKMICU";
				}
				else{
					echo "DODAJ UTAKMICU";
				}
			?>
		</caption>
		<tbody>
			<tr>
				<td colspan="2">
					<input type="hidden" name="novi" value="<?php if(!empty($id))echo $id;else echo 0;?>"/>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<label class="greska"><?php if($greska!="")echo $greska; ?></label>
				</td>
			<tr>
				<td class="lijevi">
					<label for="momcad_1"><strong>Momčad 1:</strong></label>
				</td>
				<td>
					<select name="momcad1" id="momcad1" onchange="PokaziDruguMomcad(this.value ,<?php echo $aktivni_korisnik_id; ?>,<?php echo $id; ?>)">
						<?php
						$sqlm1="SELECT m.momcad_id, m.naziv, l.liga_id,l.opis
						from momcad m INNER JOIN liga l
						ON m.liga_id = l.liga_id";
						if($aktivni_korisnik_tip==1){
							$sqlm1.=" WHERE l.moderator_id = $aktivni_korisnik_id ORDER BY m.naziv";
						}
											
						$rs=izvrsiUpit($bp,$sqlm1);
						while(list($idm,$naziv,$ligaid,$opisl)=mysqli_fetch_row($rs)){
							if(!isset($_SESSION["idm"])){
								$_SESSION["idm"]=$idm;
							}
							
							echo "<option value='$idm'";
							if($idm==$momcad_1){
								echo " selected";
							}
							echo">$naziv - $opisl</option>";
						}
						?>						
					</select>
					<?php
						if(isset($_SESSION["idm"])){
							$odbmom=$_SESSION["idm"];
							unset($_SESSION["idm"]);
						}
					?>
					<img src="images/strelica.png" onload="PokaziDruguMomcad(<?php echo $odbmom; ?>,<?php echo $aktivni_korisnik_id; ?>,<?php echo $id; ?>)">					
				</td>
			</tr>
			<tr>
				<td>
					<label for="momcad_2"><strong>Momčad 2:</strong></label>
				</td>
				<td id="drugamomcad">
					
				</td>
			</tr>
			<tr>
				<td>
					<label for="datum_vrijeme_pocetka"><strong>Datum vrijeme početka:</strong></label>
				</td>
				<td>
					<input type="text" name="datum_vrijeme_pocetka" id="datum_vrijeme_pocetka" value="<?php if(!isset($_POST['datum_vrijeme_pocetka']))echo $datum_vrijeme_pocetka; else echo $_POST['datum_vrijeme_pocetka'];?>"
						size="120" minlength="1" maxlength="50" placeholder="<?php echo date("d.m.Y h:i:s"); ?>" required="required" <?php if(isset($_GET["zavrseno"])){ echo " readonly";}?>/>
				</td>
			</tr>

			<?php
			if($id>0){
			?>
			<tr>
				<td>
					<label for="rezultat_1"><strong>Rezultat 1:</strong></label>
				</td>
				<td>
					<input type="text" name="rezultat_1" id="rezultat_1" value="<?php if(!isset($_POST['rezultat_1']))echo $rezultat_1; else echo $_POST['rezultat_1'];?>"
						size="120" minlength="1" maxlength="50" placeholder="Ime treba započeti velikim početnim slovom" required="required" readonly/>
				</td>
			</tr>
			
			<tr>
				<td>
					<label for="rezultat_2"><strong>Rezultat 2:</strong></label>
				</td>
				<td>
					<input type="text" name="rezultat_2" id="rezultat_2" value="<?php if(!isset($_POST['rezultat_2']))echo $rezultat_2; else echo $_POST['rezultat_2'];?>"
						size="120" minlength="1" maxlength="50" placeholder="Ispravan oblik elektroničke pošte je nesto@nesto.nesto" required="required" readonly/>
				</td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td>
					<label for="opis"><strong>Opis:</strong></label>
				</td>
				<td>
					<input type="text" name="opis" id="opis" value="<?php if(!isset($_POST['opis']))echo $opis; else echo $_POST['opis'];?>"
						size="120" minlength="1" maxlength="50" placeholder="Opis" required="required"/>
				</td>
			</tr>
			
			<tr>
				<td colspan="2" style="text-align:center;">
					<?php
						if(isset($id)&&$aktivni_korisnik_id==$id||!empty($id))echo '<input type="submit" name="submit" value="Pošalji"/>';
						else echo '<input type="submit" name="reset" value="Izbriši"/><input type="submit" name="submit" value="Pošalji"/>';
					?>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<?php
	}

	if(isset($_GET["utakmicakraj"])){
	$id=$_GET["utakmicakraj"];
	$utakmica=UtakmicaInfo($id);
	echo "<br>Unosite rezultat za utakmicu ".$utakmica;
		$momcadi = explode(" - ",$utakmica);
	?>

<form method="POST" action="utakmica.php">
	<table>
		<tbody>
			<tr>
				<td colspan="2">
					<input type="hidden" name="utakmicaid" value="<?php echo $id;?>"/>
				</td>
			</tr>
			
			<tr>
				<td>
					<label for="rezultat_1"><strong>Rezultat <?php echo $momcadi[0];?>:</strong></label>
				</td>
				<td>
					<input type="text" name="rezultat_1" id="rezultat_1" value=""> <?php if(isset($_SESSION["rez1"])) {echo $_SESSION["rez1"]; unset($_SESSION["rez1"]); }?>
				</td>
			</tr>
			
			<tr>
				<td>
					<label for="rezultat_2"><strong>Rezultat <?php echo $momcadi[1];?>:</strong></label>
				</td>
				<td>
					<input type="text" name="rezultat_2" id="rezultat_2" value=""> <?php if(isset($_SESSION["rez2"])) {echo $_SESSION["rez2"]; unset($_SESSION["rez2"]); }?>
				</td>
			</tr>		
			<tr>
				<td colspan="2" style="text-align:center;">
					<input type="submit" name="Zavrsi" value="Pošalji"/>
				</td>
			</tr>
		</tbody>
	</table>
</form>

	<?php

	}

	if(isset($_POST["Zavrsi"])){

		$utakmicaid=$_POST["utakmicaid"];
		$rez1=$_POST["rezultat_1"];
		$rez2=$_POST["rezultat_2"];

		if($rez1=="" || empty($rez1)){
			$_SESSION["rez1"]="Rezultat1 je prazan";
		}

		if($rez2=="" || empty($rez2)){
			$_SESSION["rez2"]="Rezultat2 je prazan";
		}

		if(isset($_SESSION["rez1"]) || isset($_SESSION["rez2"])){
			header("Location: utakmica.php?utakmicakraj=$utakmicaid");
			return false;
		}

		$sql="update utakmica set rezultat_1 = '$rez1', rezultat_2 = '$rez2' where utakmica_id=".$utakmicaid;
		$rs = izvrsiUpit($bp,$sql);

		$sqllistic = "select ocekivani_rezultat, korisnik_id from listic where utakmica_id = ".$utakmicaid;
		$rs = izvrsiUpit($bp,$sqllistic);

		while(list($ocekrez,$korid)=mysqli_fetch_row($rs)){

		if(($ocekrez==1 && $rez1>$rez2) || ($ocekrez==2 && $rez1<$rez2)){
			$sqlupd="update listic set status = 'D' where utakmica_id = ".$utakmicaid." and korisnik_id=".$korid." and status='P'";
		}
		else{
			$sqlupd="update listic set status = 'N' where utakmica_id = ".$utakmicaid." and korisnik_id=".$korid." and status='P'";
		}
		$rs1 = izvrsiUpit($bp,$sqlupd);
		}

		header("Location:utakmice.php");
	}
	
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>