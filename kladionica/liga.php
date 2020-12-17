<?php
	include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>
<?php
	$greska="";
	if(isset($_POST['SubmitLiga'])){
			$id=$_POST['novi'];
			$naziv=$_POST['naziv'];
			$slika=$_POST['slika'];
			$video=$_POST['video'];
            $opis=$_POST['opis'];
            $moderator_id=$_POST['moderator_id'];
	

			if($id==0){
				$sql="INSERT INTO liga
				(moderator_id,naziv,slika,video,opis)
				VALUES
				('$moderator_id','$naziv','$slika','$video','$opis');
				";
			}
			else{
				$sql="UPDATE liga SET
					moderator_id='$moderator_id',
                    naziv='$naziv',
                    slika='$slika',
                    video='$video',
                    opis='$opis'
					WHERE liga_id='$id'
				";
			}
			izvrsiUpit($bp,$sql);
			header("Location:index.php");
		}
	
	if(isset($_GET['liga'])){
		$id=$_GET['liga'];
		$sql="SELECT liga_id,moderator_id,naziv,slika,video,opis FROM liga WHERE liga_id='$id'";
		$rs=izvrsiUpit($bp,$sql);
		list($id,$moderator_id,$naziv,$slika,$video,$opis)=mysqli_fetch_row($rs);
	}
	else{
        $moderator_id="";
        $naziv="";
		$slika="";
		$video="";
		$opis="";
    }
    
	if(isset($_POST['reset']))header("Location:index.php");
?>
<form method="POST" action="<?php if(isset($_GET['liga']))echo "liga.php?liga=$id";else echo "liga.php";?>">
	<table>
		<caption>
			<?php
				
				if(!empty($id))echo "Uredi ligu";
				else echo "Dodaj ligu";
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
					<input type="text" name="naziv" id="naziv"
						
						value="<?php if(!isset($_POST['naziv']))echo $naziv; else echo $_POST['naziv'];?>" size="120" minlength="3" maxlength="50"
						placeholder="Naziv treba uključiti minimalno 3 znaka i započeti velikim početnim slovom"  required="required"/>
				</td>
			</tr>
            <tr>
				<td>
					<label for="slika"><strong>Slika:</strong></label>
				</td>
				<td>
					<input type="url" name="slika" id="slika" size="120" placeholder="Ispravan oblik poveznice je https://www.ligue1.com/"  required="required"
						maxlength="255" value="<?php if(!isset($_POST['slika']))echo $slika; else echo $_POST['slika'];?>"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="video"><strong>video:</strong></label>
				</td>
				<td>
					<input type="url" name="video" id="video" value="<?php if(!isset($_POST['video']))echo $video; else echo $_POST['video'];?>"
						size="120" minlength="1" maxlength="50" placeholder="Ispravan oblik poveznice je https://www.youtube.com/watch?v=tOXiIm8yLUU" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="opis"><strong>opis:</strong></label>
				</td>
				<td>
					<input type="text" name="opis" id="opis" value="<?php if(!isset($_POST['opis']))echo $opis; else echo $_POST['opis'];?>"
						size="120" minlength="1" maxlength="50" placeholder="opis treba započeti velikim početnim slovom" required="required"/>
				</td>
			</tr>
            <?php
				if($_SESSION['aktivni_korisnik_tip']==0){
			?>
			<tr>
				<td><label for="moderatorid"><strong>Moderator:</strong></label></td>
				<td>
					<select id="moderator_id" name="moderator_id">
						<?php
					$moderatori="select korisnik_id, korisnicko_ime from korisnik where tip_korisnika_id = 1";
					$rs1 = izvrsiUpit($bp,$moderatori);
							while(list($idk,$korime)=mysqli_fetch_row($rs1)){
								echo "<option value='$idk'";
								if($idk==$moderator_id){
									echo " selected";
								}
								echo ">$korime</option>";
							}
						?>
					</select>
				</td>
			</tr>
			<?php
				}
			?>
			
			
			<tr>
				<td colspan="2" style="text-align:center;">
					<?php
						if(isset($id)&&$aktivni_korisnik_id==$id||!empty($id))echo '<input type="submit" name="SubmitLiga" value="Pošalji"/>';
						else echo '<input type="submit" name="reset" value="Izbriši"/><input type="submit" name="SubmitLiga" value="Pošalji"/>';
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
