<?php
	include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>
<?php
	$greska="";
	if(isset($_POST['submit'])){
		print_r($_POST);
		foreach($_POST as $key => $value){
			if(strlen($value)==0 && ($key != "slika" && $key != "imagehidden"))
			$greska="Sva polja za unos su obavezna";
		}
		
		if(empty($greska)){
			$id=$_POST['novi'];
			$tip_id=$_POST['tip_id'];
			$kor_ime=$_POST['kor_ime'];
			$lozinka=$_POST['lozinka'];
			$ime=$_POST['ime'];
			$prezime=$_POST['prezime'];
			$email=$_POST['email'];
			
			$currentImage = $_POST['imagehidden'];
					
			$slikekorisnika = "korisnici/";	

			$fileName = basename($_FILES['slika']['name']);

			
			if($fileName != ""){				
			$slika = $slikekorisnika.$fileName;
			
			if(file_exists($slika)){
				$newname=str_replace(".","_old_".date("dmYHis").".",$slika);
				rename($slika,$newname);
			}
			
			$upload = move_uploaded_file($_FILES['slika']['tmp_name'],$slika);
			}
			else
			{
				if($currentImage != ""){
					$slika = $currentImage;
				}
				else
				{
					$slika = "korisnici/nophoto.jpg";
				}						
			}

			if($id==0){
				$sql="INSERT INTO korisnik
				(tip_korisnika_id,korisnicko_ime,lozinka,ime,prezime,email,slika)
				VALUES
				('$tip_id','$kor_ime','$lozinka','$ime','$prezime','$email','$slika');
				";
			}
			else{
				$sql="UPDATE korisnik SET
					tip_korisnika_id='$tip_id',
					ime='$ime',
					prezime='$prezime',
					lozinka='$lozinka',
					email='$email',
					slika='$slika'
					WHERE korisnik_id='$id'
				";
			}
			izvrsiUpit($bp,$sql);
			header("Location:korisnici.php");
		}
	}
	if(isset($_GET['korisnik'])){
		$id=$_GET['korisnik'];
		if($aktivni_korisnik_tip==2)$id=$_SESSION["aktivni_korisnik_id"]; 
		$sql="SELECT korisnik_id,tip_korisnika_id,korisnicko_ime,lozinka,ime,prezime,email,slika FROM korisnik WHERE korisnik_id='$id'";
		$rs=izvrsiUpit($bp,$sql);
		list($id,$tip_id,$kor_ime,$lozinka,$ime,$prezime,$email,$slika)=mysqli_fetch_row($rs);
	}
	else{
		$tip_id="";
		$kor_ime="";
		$lozinka="";
		$ime="";
		$prezime="";
		$email="";
		$slika="";
	}
	if(isset($_POST['reset']))header("Location:korisnik.php");
?>
<form method="POST" enctype="multipart/form-data" action="<?php if(isset($_GET['korisnik']))echo "korisnik.php?korisnik=$id";else echo "korisnik.php";?>">
	<table>
		<caption>
			<?php
				if(isset($id)&&$aktivni_korisnik_id==$id)echo "Uredi moje podatke";
				else if(!empty($id))echo "Uredi korisnika";
				else echo "Dodaj korisnika";
			?>
		</caption>
		<tbody>
			<tr>
				<td colspan="2">
					<input type="hidden" name="novi" value="<?php if(!empty($id))echo $id;else echo 0;?>"/>
					<input type="hidden" name="imagehidden" id="imagehidden" value="<?php echo $slika; ?>"/>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<label class="greska"><?php if($greska!="")echo $greska; ?></label>
				</td>
			</tr>
			<tr>
				<td class="lijevi">
					<label for="kor_ime"><strong>Korisničko ime:</strong></label>
				</td>
				<td>
					<input type="text" name="kor_ime" id="kor_ime"
						<?php
							if(isset($id))echo "readonly='readonly'";
						?>
						value="<?php if(!isset($_POST['kor_ime']))echo $kor_ime; else echo $_POST['kor_ime'];?>" size="120" minlength="4" maxlength="50"
						placeholder="Korisničko ime ne smije sadržavati praznine, treba uključiti minimalno 10 znakova i započeti malim početnim slovom"
						required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="ime"><strong>Ime:</strong></label>
				</td>
				<td>
					<input type="text" name="ime" id="ime" value="<?php if(!isset($_POST['ime']))echo $ime; else echo $_POST['ime'];?>"
						size="120" minlength="1" maxlength="50" placeholder="Ime treba započeti velikim početnim slovom" required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="prezime"><strong>Prezime:</strong></label>
				</td>
				<td>
					<input type="text" name="prezime" id="prezime" value="<?php if(!isset($_POST['prezime']))echo $prezime; else echo $_POST['prezime'];?>"
						size="120" minlength="1" maxlength="50" placeholder="Prezime treba započeti velikim početnim slovom" required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="lozinka"><strong>Lozinka:</strong></label>
				</td>
				<td>
					<input <?php if(!empty($lozinka))echo "type='text'"; else echo "type='password'";?>
						name="lozinka" id="lozinka" value="<?php if(!isset($_POST['lozinka']))echo $lozinka; else echo $_POST['lozinka'];?>"
						size="120" minlength="6" maxlength="50"
						placeholder="Lozinka treba sadržati minimalno 8 znakova uključujući jedno veliko i jedno malo slovo, jedan broj i jedan posebni znak"
						required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="email"><strong>E-mail:</strong></label>
				</td>
				<td>
					<input type="email" name="email" id="email" value="<?php if(!isset($_POST['email']))echo $email; else echo $_POST['email'];?>"
						size="120" minlength="5" maxlength="50" placeholder="Ispravan oblik elektroničke pošte je nesto@nesto.nesto" required="required"/>
				</td>
			</tr>
			<?php
				if($_SESSION['aktivni_korisnik_tip']==0){
			?>
			<tr>
				<td><label for="tip_id"><strong>Tip korisnika:</strong></label></td>
				<td>
					<select id="tip_id" name="tip_id">
						<?php
							if(isset($_POST['tip_id'])){
								echo '<option value="0"';if($_POST['tip_id']==0)echo " selected='selected'";echo'>Administrator</option>';
								echo '<option value="1"';if($_POST['tip_id']==1)echo " selected='selected'";echo'>Voditelj</option>';
								echo '<option value="2"';if($_POST['tip_id']==2)echo " selected='selected'";echo'>Korisnik</option>';
							}
							else{
								echo '<option value="0"';if($tip_id==0)echo " selected='selected'";echo'>Administrator</option>';
								echo '<option value="1"';if($tip_id==1)echo " selected='selected'";echo'>Voditelj</option>';
								echo '<option value="2"';if($tip_id==2)echo " selected='selected'";echo'>Korisnik</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<?php
				}
			?>
			<tr>
				<td>
					<label for="slika"><strong>Slika:</strong></label>
				</td>
				<td>
				<input type="file" name="slika" id="slika">
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
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>
