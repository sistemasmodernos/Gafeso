<?php
require('controles.php');
inicio("manbus");
$idBus=$_GET['idBus'];
if(Mantenimiento("bus",array("idBus"=>$idBus),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>