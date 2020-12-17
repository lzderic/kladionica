<?php
include("baza.php");
session_start();

$bp=spojiSeNaBazu();
$moderator=$_GET["moderatorid"];
$momcad1=$_GET["momcad1"];
$utakmicaid=$_GET["utakmicaid"];

$sqlliga = "SELECT l.liga_id
from momcad m INNER JOIN liga l
ON m.liga_id = l.liga_id WHERE ";
if($_SESSION['aktivni_korisnik_tip']==1){
    $sqlliga .= "l.moderator_id = $moderator AND ";
}
$sqlliga .= "m.momcad_id = ".$momcad1;
$rs=izvrsiUpit($bp,$sqlliga);

list($ligaid)=mysqli_fetch_row($rs);

$sql="SELECT m.momcad_id, m.naziv, l.liga_id, l.opis
from momcad m INNER JOIN liga l
ON m.liga_id = l.liga_id WHERE ";
if($_SESSION['aktivni_korisnik_tip']==1){
    $sql .= "l.moderator_id = $moderator AND ";
}
$sql .= "l.liga_id = $ligaid
AND m.momcad_id <> $momcad1";
$rs=izvrsiUpit($bp,$sql);

if($utakmicaid>0){


$zaodabir="SELECT
u.momcad_2
FROM utakmica u INNER JOIN momcad m
ON u.momcad_1 = m.momcad_id
WHERE m.liga_id = $ligaid AND u.momcad_1 = $momcad1 AND u.utakmica_id = ".$utakmicaid;
$rs1=izvrsiUpit($bp,$zaodabir);
list($momcad2)=mysqli_fetch_row($rs1);
}
else
{
    $momcad2=0;
}
echo "<select name='momcad2' id='momcad2'>";
while(list($momcadid,$momcadnaziv,$ligaid,$ligaopis)=mysqli_fetch_row($rs)){
    echo "<option value='$momcadid'";
    if($momcadid==$momcad2){
        echo " selected";
    }
    echo ">$momcadnaziv - $ligaopis</option>";
}
echo "</select> <img src='images/strelica.png'>";

zatvoriVezuNaBazu($bp);
?>