<?php
require('controles.php');
inicio("manruta");
$idruta = $_GET['idruta'];
if(Mantenimiento("ruta",array("idruta"=>$idruta),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>