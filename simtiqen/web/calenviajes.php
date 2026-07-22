<?php 
  include_once('rediscore.php');
  $redis = new rediscore();

?>


<html>
<head>
   <link rel="stylesheet" href="css/general.css" type="text/css" />
   <link rel="stylesheet" href="css/tiquetes.css" type="text/css" />
</head>
<body>
<div>
<div id='calendario'>
<div>
  <div style='float:left;'><a href='#'>Anterior</a></div>
  <div style='display:inline;margin-left:100px;'>
          <?php 
            echo "<a href='#' onclick=\"selectviaje('".date('d/m/Y',time())."',0);\">Hoy</a>"  ; 
            ?>
  </div>
  <div style='float:right;'><a href='#'>Siguiente</a></div>
</div>
<div style='clear:both'></div>
<?php

include_once('lib/nucleo.php');
$fechafin = date('Y-m-d',strtotime($_GET["inicio"]." + ".$_GET["dias"]." days"));
$llaveredis="gaf-cale-".$_GET["ruta"]."-".$_GET["estacion"]."-".$_GET["inicio"]."-".$fechafin;
$viajeserial = $redis->get($llaveredis);
if($viajeserial!=''){
   $res=unserialize($viajeserial);
}
else{
   $res =  Viajes($_GET["ruta"],$_GET["estacion"],$_GET["inicio"],$fechafin);
   $viajeserial= serialize($res);
   $redis->set($llaveredis,$viajeserial,5);
}

//$res =  Viajes($_GET["ruta"],$_GET["estacion"],$_GET["inicio"],$fechafin);
$dias = array("Domingo","Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","Sabado");

 for($x=0;$x<count($res);$x++){
   if($x<17){
     $fecha1 = date('d/m/Y', strtotime($res[$x]["fecha"]) );
     $falta =$res[$x]["cantpas"] - $res[$x]["vendidos"];
    if (($x % 2 )==0)
        echo "<div class='calen cale1' onclick=\"selectviaje('".$fecha1."',".$res[$x]["idviaje"].");\">"  ;
    else
        echo " <div class='calen cale2' onclick=\"selectviaje('".$fecha1."',".$res[$x]["idviaje"].");\">"  ;
    echo $dias[date('w',strtotime($res[$x]["fecha"]))]. "  ";
    echo  date('d/m/Y',strtotime($res[$x]["fecha"]) ). " <span > " . $res[$x]["hora"] . "</span><br/>";
    echo "Bus: ". $res[$x]["cantpas"].
    " vendidos: ".$res[$x]["vendidos"]. " dispo: ".$falta." De pie ".$res[$x]["depie"];
    echo "</div>";
  }
 }
 
 
?>
</div>
</div>
</body>
</html>
