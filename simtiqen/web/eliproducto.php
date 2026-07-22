<?php
require('controles.php');
inicio("manprodu");
$idProducto = $_GET['idProducto'];
if(Mantenimiento("producto",array("idProducto"=>$idProducto),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>