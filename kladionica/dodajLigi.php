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
			$naziv=$_POST['naziv'];
			$opis=$_POST['opis'];
			$liga_id=$_POST['liga_id'];

			if($id==0){
				$sql="INSERT INTO momcad
				(naziv,opis,liga_id)
				VALUES
				($naziv,'$opis', '$liga_id');
				";
			}
			else{
				$sql="UPDATE momcad SET
					naziv='$naziv',
					opis='$opis',
					liga_id='$liga_id'
					WHERE momcad_id='$id'
				";
			}
			izvrsiUpit($bp,$sql);
			header("Location:momcadi.php");
		}
	}
	if(isset($_GET['momcad'])){
		$id=$_GET['momcad'];
		$sql="SELECT * FROM momcad WHERE momcad_id='$id'";
		$rs=izvrsiUpit($bp,$sql);
		list($id,$liga_id,$naziv,$opis)=mysqli_fetch_row($rs);
	}
	else{
		$liga_id="";
		$naziv="";
		$opis="";
	}
	if(isset($_POST['reset']))header("Location:urediMomcad.php");
?>
<form method="POST" action="<?php if(isset($_GET['momcad']))echo "urediMomcad.php?momcad=$id";else echo "urediMomcad.php";?>">
	<table>
		<caption>
			<?php
				 echo "Uredi momčad";
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
			</tr>
			<tr>
				<td class="lijevi">
					<label for="naziv"><strong>Naziv:</strong></label>
				</td>
				<td>
					<input type="text" name="naziv" id="naziv" value="<?php if(!isset($_POST['naziv']))echo $naziv; else echo $_POST['naziv'];?>"
						size="120" minlength="1" maxlength="50" placeholder="naziv treba započeti velikim početnim slovom" required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="opis"><strong>Opis:</strong></label>
				</td>
				<td>
					<input type="text" name="opis" id="opis" value="<?php if(!isset($_POST['opis']))echo $opis; else echo $_POST['opis'];?>"
						size="120" minlength="1" maxlength="50" placeholder="opis treba započeti velikim početnim slovom" required="required"/>
				</td>
			</tr>
			<tr>
			<tr>
				<td><label for="liga_id"><strong> Lige :</strong></label></td>
				<td>
					<select id="liga_id" name="liga_id">
						<?php
							if(isset($_POST['liga_id'])){
								echo '<option value="1"';if($_POST['liga_id']==1)echo " selected='selected'";echo'>HNL</option>';
								echo '<option value="2"';if($_POST['liga_id']==2)echo " selected='selected'";echo'>Barclays Premier League</option>';
								echo '<option value="3"';if($_POST['liga_id']==3)echo " selected='selected'";echo'>La Liga</option>';
								echo '<option value="4"';if($_POST['liga_id']==4)echo " selected='selected'";echo'>Serie A</option>';
								echo '<option value="5"';if($_POST['liga_id']==5)echo " selected='selected'";echo'>Ligue 1</option>';
								echo '<option value="6"';if($_POST['liga_id']==6)echo " selected='selected'";echo'>Prijateljske utakmice</option>';
							}
							else{
								echo '<option value="1"';if($liga_id==1)echo " selected='selected'";echo'>HNL</option>';
								echo '<option value="2"';if($liga_id==2)echo " selected='selected'";echo'>Barclays Premier League</option>';
								echo '<option value="3"';if($liga_id==3)echo " selected='selected'";echo'>La Liga</option>';
								echo '<option value="4"';if($liga_id==4)echo " selected='selected'";echo'>Serie A</option>';
								echo '<option value="5"';if($liga_id==5)echo " selected='selected'";echo'>Ligue 1</option>';
								echo '<option value="6"';if($liga_id==6)echo " selected='selected'";echo'>Korisnik</option>';
							}
						?>
					</select>
				</td>
			</tr>
			
				<td colspan="2" style="text-align:center;">
					<?php
						if(isset($id)||!empty($id))echo '<input type="submit" name="submit" value="Pošalji"/>';
						else echo '<input type="submit" name="reset" value="Izbriši"/><input type="submit" name="submit" value="Pošalji"/>';
					?>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<?php
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>