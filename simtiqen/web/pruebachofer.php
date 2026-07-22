<?php 
require_once ("lib/Chofer.php");
require_once ("lib/bitacora.php");

$elChofer = new Chofer();
if ($elChofer->CargarId(1)){
  echo $elChofer->ToString();
}else{
  echo "No lo cargó";
}  
  echo "<br>";
$elChofer = new Chofer();
$elChofer->bitacora = new Bitacora();
$elChofer->nombre = "Chofer 2";
$elChofer->cedula = "Chofer 2";
$elChofer->activo = 1;
if ($elChofer->Guardar(null)){
  echo $elChofer->ToString();
}else{
  echo "No lo Agregó";
}
echo "<br>";
$elChofer->nombre = "Chofer 2 Modificada";
$elChofer->cedula = "2-222-222 Modificada";
if ($elChofer->Guardar(null)){
  echo $elChofer->ToString();
}else{
  echo "No lo modificó";
}
echo "<br>";
if ($elChofer->Borrar(null)){
  echo "Borrado";
}else{
  echo "No lo borró";
}
echo "<br>";
?>
