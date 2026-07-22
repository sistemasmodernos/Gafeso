<?php 
require_once ("lib/cobrador.php");
require_once ("lib/bitacora.php");

$elCobrador = new Cobrador();
if ($elCobrador->CargarId(1)>0){
  echo $elCobrador->ToString();
}else{
  echo "No lo cargó";
}  
  echo "<br>";
$elCobrador = new Cobrador();
$elCobrador->bitacora = new Bitacora();
$elCobrador->nombre = "Cobrador 2";
$elCobrador->cedula = "Cobrador 2";
$elCobrador->activo = 1;
if ($elCobrador->Guardar(null)){
  echo $elCobrador->ToString();
}else{
  echo "No lo Agregó";
}
echo "<br>";
$elCobrador->nombre = "Cobrador 2 Modificada";
$elCobrador->cedula = "2-222-222 Modificada";
if ($elCobrador->Guardar(null)){
  echo $elCobrador->ToString();
}else{
  echo "No lo modificó";
}
echo "<br>";
if ($elCobrador->Borrar(null)){
  echo "Borrado";
}else{
  echo "No lo borró";
}
echo "<br>";
?>
