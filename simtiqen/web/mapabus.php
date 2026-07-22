<?php
include_once('lib/nucleo.php');
$viaje= $_GET["viaje"];
$esta = $_GET["estacion"];
$dat = DatosViaje($viaje,$esta);
$cantasie= $dat["tamano"]; 
$fichero='formatosbus/bus'.str_replace(" ", "", $dat["bus"]).".php";
if(!file_exists($fichero) ){ 
  $fichero='formatosbus/bus'.$cantasie.".php";
  if(!file_exists($fichero) ){ 
     $fichero='formatosbus/bus50.php';
  }
}
 
$filas=file($fichero);
// iniciamos contador y la fila a cero
$xconta = count($filas);
$i=0;
// mientras exista una fila

while($i<$xconta){
    echo $filas[$i];
    if(stristr($filas[$i],"formabus"))
       echo "<h2> Placa ".$dat["bus"]."</h2> <br>"; 
    $i++;
}

?>