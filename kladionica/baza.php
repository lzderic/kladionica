<?php

	function spojiSeNaBazu(){
		$veza=mysqli_connect(POSLUZITELJ,BAZA_KORISNIK,BAZA_LOZINKA);
		if(!$veza)echo "GREŠKA: Problem sa spajanjem u datoteci baza.php funkcija spojiSeNaBazu: ".mysqli_connect_error();
		mysqli_select_db($veza,BAZA);
		if(mysqli_error($veza)!=="")echo "GREŠKA: Problem sa odabirom baze u baza.php funkcija spojiSeNaBazu: ".mysqli_error($veza);
		mysqli_set_charset($veza,"utf8");
		if(mysqli_error($veza)!=="")echo "GREŠKA: Problem sa odabirom baze u baza.php funkcija spojiSeNaBazu: ".mysqli_error($veza);
		return $veza;
	}

	function izvrsiUpit($veza,$upit){
		$rezultat=mysqli_query($veza,$upit);
		if(!$rezultat){
			die ("GREŠKA: Problem sa upitom: ".$upit." : u datoteci baza.php funkcija izvrsiUpit: ".mysqli_error($veza));
		}
		return $rezultat;
	}

	function zatvoriVezuNaBazu($veza){
		mysqli_close($veza);
	}

	function UtakmicaInfo($id){
		$bp=spojiSeNaBazu();
		$sql="SELECT
		u.utakmica_id,
		concat(m1.naziv,' - ',m2.naziv) AS 'Utakmica'
		FROM utakmica u
		INNER JOIN momcad m1
		ON u.momcad_1 = m1.momcad_id
		INNER JOIN momcad m2
		ON u.momcad_2 = m2.momcad_id
		WHERE u.utakmica_id = ".$id;
		$sp = izvrsiUpit($bp,$sql);

		list($idutakmica,$nazivutakmica)=mysqli_fetch_row($sp);
		return $nazivutakmica;
	}
?>