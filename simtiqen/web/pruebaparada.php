<?php 
require_once ("lib/parada.php");
require_once ("lib/bitacora.php");

$laParada = new Parada();
if ($laParada->CargarId(1)){
  echo $laParada->ToString();
}else{
  echo "No lo cargó";
}  
$laParada = new Parada();
$laParada->bitacora = new Bitacora();
$laParada->nombre = "Parada 2";
$laParada->activo = 1;
if ($laParada->Guardar(null)){
  echo $laParada->ToString();
}else{
  echo "No lo Agregó";
}
$laParada->nombre = "Parada 2 Modificada";
if ($laParada->Guardar(null)){
  echo $laParada->ToString();
}else{
  echo "No lo modificó";
}
if ($laParada->Borrar(null)){
  echo "Borrada";
}else{
  echo "No lo borró";
}

?>
