<?php 
require_once ("lib/Empresa.php");
require_once ("lib/bitacora.php");

$elEmpresa = new Empresa();
if ($elEmpresa->CargarId(1)){
  echo $elEmpresa->ToString();
}else{
  echo "No lo cargó";
}  
  echo "<br>";
$elEmpresa = new Empresa();
$elEmpresa->bitacora = new Bitacora();
$elEmpresa->nombre = "Empresa 2";
$elEmpresa->cedula = "Empresa 2";
$elEmpresa->activo = 1;
if ($elEmpresa->Guardar(null)){
  echo $elEmpresa->ToString();
}else{
  echo "No lo Agregó";
}
echo "<br>";
$elEmpresa->nombre = "Empresa 2 Modificada";
$elEmpresa->cedula = "2-222-222 Modificada";
if ($elEmpresa->Guardar(null)){
  echo $elEmpresa->ToString();
}else{
  echo "No lo modificó";
}
echo "<br>";
if ($elEmpresa->Borrar(null)){
  echo "Borrado";
}else{
  echo "No lo borró";
}
echo "<br>";
?>
