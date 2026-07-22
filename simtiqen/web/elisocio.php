<?php
require('controles.php');
inicio("mansocio");
$idsocio=$_GET['idsocio'];
if(Mantenimiento("socio",array("idsocio"=>$idsocio),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>