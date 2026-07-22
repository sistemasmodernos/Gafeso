<?php 
require_once ("lib/Usuario.php");
require_once ("lib/bitacora.php");

$elUsuario = new Usuario();
if ($elUsuario->CargarId(1)){
  echo $elUsuario->ToString();
}else{
  echo "No lo cargó";
}  
echo "<br>";
$elUsuario = new Usuario();
$elUsuario->bitacora = new Bitacora();
$elUsuario->nombre = "Usuario 2";
$elUsuario->clave = "Clave 2";
$elUsuario->activo = 1;
if ($elUsuario->Guardar(null)){
  echo $elUsuario->ToString();
}else{
  echo " No lo Agregó";
}
echo "<br>";
$elUsuario->nombre = "Usuario 2 Modificada";
$elUsuario->clave = "2-222-222 Modificada";
if ($elUsuario->Guardar(null)){
  echo $elUsuario->ToString();
}else{
  echo " No lo modificó";
}
echo "<br>";
if ($elUsuario->Borrar(null)){
  echo " Borrado";
}else{
  echo " No lo borró";
}
echo "<br>";
?>
