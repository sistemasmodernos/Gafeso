<?php
include_once('lib/mascaras.php');
include_once('controles.php');
include_once('lib/nucleo.php');
include_once('lib/utiles.php');
inicio("sinpermiso");
$preli = false;
if(isset($_GET["tiptiq"]))
 $tiptiq=$_GET["tiptiq"];
else
 $tiptiq="T";

if (isset($_GET["rp"]))
    if ($_GET["rp"] == '2')
      {
        $esreimpre = false;
        $preli     = true;
      }
    else
        $esreimpre = true;
else
    $esreimpre = false;


if (isset($_GET["id"]))
  {
    $tiqs  = explode(",", $_GET["id"]);
    $cad   = "";
    $corte = "";
    for ($x = 0; $x < count($tiqs); $x++)
      {
        $cad .= $corte . ImprimeTiquete($tiqs[$x]);
        $corte = "_i_";
        if ($esreimpre)
            GuardaReimpreTiq($tiqs[$x]);
        if (!$preli)
            GuardaImpreTiq($tiqs[$x]);
        
      }
    echo $cad;
    
  }
else
  {
    $fac = str_pad($_GET["fact"], 6, "0", STR_PAD_LEFT);
    $imp = $_GET["impre"];
    if(!ValidaReimpresion($imp, $fac)){
        echo "No tiene permiso para reimprimir este tiquete";
      return true;
      }
      
    $res = ImprimeTiqueteF($imp, $fac,$tiptiq);
    if ($esreimpre)
      {
        GuardaReimpreTiqF($imp, $fac);
        $res .= " reimpre ";
      }
    if (!$preli)
        GuardaImpreTiqF($imp, $fac);
    echo $res;
  }
?>
