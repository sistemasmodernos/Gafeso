<?php 
require_once ("mysql.php");
$conexion = database_connect();
$tira = "select * from parada";
$resultado = query($tira,$conexion);
echo num_rows($resultado);
?>
