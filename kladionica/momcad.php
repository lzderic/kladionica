<?php
	include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>
<?php
	$greska="";
	if(isset($_POST['submit'])){

			$id=$_POST['novi'];
			$liga_id=$_POST['liga_id'];
			$naziv=$_POST['naziv'];
			$opis=$_POST['opis'];

			if($id==0){
				$sql="INSERT INTO momcad
				(liga_id,naziv,opis)
				VALUES
				('$liga_id','$naziv','$opis');
				";
			}
			else{
				$sql="UPDATE momcad SET
					liga_id='$liga_id',
					naziv='$naziv',
					opis='$opis'
					WHERE momcad_id='$id'
				";
			}
			izvrsiUpit($bp,$sql);
			header("Location:momcadi.php");
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
	if(isset($_POST['reset']))header("Location:momcad.php");
?>
<form method="POST" action="<?php if(isset($_GET['momcad']))echo "momcad.php?momcad=$id";else echo "momcad.php";?>">
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
				<td><label for="liga_id"><strong>Liga :</strong></label></td>
				<td>
					<select id="liga_id" name="liga_id">
						
						<?php
						$lige="select liga_id, naziv from liga";
						$rs1 = izvrsiUpit($bp,$lige);
								while(list($idl,$nazivl)=mysqli_fetch_row($rs1)){
									echo "<option value='$idl'";
									if($idl==$liga_id){
										echo " selected";
									}
									echo ">$nazivl</option>";
								}		
						?>
					</select>
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
						size="120" minlength="1" maxlength="50" placeholder="opis treba započeti velikim početnim slovom" />
				</td>
			</tr>
			<tr>
			
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