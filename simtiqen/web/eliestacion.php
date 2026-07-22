<?php
require('controles.php');
inicio("manesta");
$idestacion=$_GET['idestacion'];
if(Mantenimiento("estacion",array("idestacion"=>$idestacion),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>