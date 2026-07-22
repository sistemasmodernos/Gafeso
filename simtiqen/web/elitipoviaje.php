<?php
require('controles.php');
inicio("mantipoviaje");
$idtipoviaje=$_GET['idtipoviaje'];
if(Mantenimiento("tipoviaje",array("idTipoViaje"=>$idtipoviaje),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>