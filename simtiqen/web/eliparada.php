<?php
require('controles.php');
inicio("manpara");
$idparada=$_GET['idparada'];
if(Mantenimiento("parada",array("idparada"=>$idparada),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>