<?php 
require_once ("lib/cliente.php");
require_once ("lib/bitacora.php");

$elCliente = new Cliente();
if ($elCliente->CargarId(1)>0){
  echo $elCliente->ToString();
}else{
  echo "No lo cargó";
}  
  echo "<br>";
$elCliente = new Cliente();
$elCliente->bitacora = new Bitacora();
$elCliente->nombre = "Cliente 2";
$elCliente->cedula = "Cliente 2";
$elCliente->fechanac = "1969-05-24";
$elCliente->email = "correo@midominio.com";
$elCliente->telefono = "666-6666";
$elCliente->activo = 1;
if ($elCliente->Guardar(null)){
  echo $elCliente->ToString();
}else{
  echo "No lo Agregó";
}
echo "<br>";
$elCliente->nombre = "Cliente 2 Modificado";
$elCliente->cedula = "2-222-222 Modificado";
if ($elCliente->Guardar(null)){
  echo $elCliente->ToString();
}else{
  echo "No lo modificó";
}
echo "<br>";
if ($elCliente->Borrar(null)){
  echo "Borrado";
}else{
  echo "No lo borró";
}
echo "<br>";
?>
