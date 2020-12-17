<?php
	include("zaglavlje.php");
	$bp=spojiSeNaBazu();
?>
<?php
	$greska="";
	if(isset($_POST['submit'])){
		$utakmicaid = $_POST["utakmicaid"];
		$ocekrez = $_POST["ocekivani_rezultat"];
		$listic_id = $_POST["listicid"];
	
	if($listic_id==0){
		$sql="INSERT INTO listic (korisnik_id,utakmica_id,ocekivani_rezultat,status) VALUES ('$aktivni_korisnik_id','$utakmicaid','$ocekrez','O')";
	}
	else
	{
		$sql="update listic set ocekivani_rezultat = '$ocekrez' where listic_id = ".$listic_id;
	}
	

			izvrsiUpit($bp,$sql);
			header("Location:statusListica.php");
	}
		
	
	if(isset($_GET['utakmica'])){
		$id=$_GET['utakmica'];
		$sql="SELECT listic_id,korisnik_id,utakmica_id,ocekivani_rezultat,listic.status FROM listic WHERE utakmica_id='$id'";
		$rs=izvrsiUpit($bp,$sql);
		list($listic_id,$korisnik_id,$utakmica_id,$ocekivani_rezultat,$status)=mysqli_fetch_row($rs);
	}
	else{
		$listic_id=0;
        $korisnik_id="";
		$utakmica_id="";
		$ocekivani_rezultat="";
		$status="";
	}
	
	if(isset($_GET['azuriraj'])){
		$idlistic=$_GET['listicid'];
		$sql="select * from listic where listic_id=".$idlistic;
		$rs=izvrsiUpit($bp,$sql);
		list($listic_id,$korisnik,$id,$ocek_rez,$status)=mysqli_fetch_array($rs);
	}
	else
	{
		$listic_id=0;
		$ocek_rez="";
	}
    
	if(isset($_POST['reset']))header("Location:stvoriListic.php");
?>
<p>Kladite se na utakmicu: <?php echo UtakmicaInfo($id);?></p>
<form method="POST" action="stvoriListic.php">
	<table>
		<caption>
			<?php
				
				 echo "Stvori listić";
			?>
		</caption>
		<tbody>
			<input type="hidden" name="utakmicaid" id="utakmicaid" value="<?php echo $id;?>">
			<input type="hidden" name="listicid" id="listicid" value="<?php echo $listic_id;?>">
			<tr>
				<td colspan="2" style="text-align:center;">
					<label class="greska"><?php if($greska!="")echo $greska; ?></label>
				</td>
			</tr>			
			<tr>
				<td >
					<label for="ocekivani_rezultat"><strong>Očekivani rezultat:</strong></label>
				</td>
				<td>
					<select name="ocekivani_rezultat" id="ocekivani_rezultat">
						<option value="0" <?php if($ocek_rez==0) echo " selected"; ?>>0 - neriješeno</option>
						<option value="1" <?php if($ocek_rez==1) echo " selected"; ?>>1 - prva momčad dobiva</option>
						<option value="2" <?php if($ocek_rez==2) echo " selected"; ?>>2 - druga momčad dobiva</option>
					</select>
				</td>
			</tr>			
			<tr>
				<td colspan="2" style="text-align:center;">
				<input type="submit" name="submit" value="Spremi"/>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<?php
	zatvoriVezuNaBazu($bp);
	include("podnozje.php");
?>
