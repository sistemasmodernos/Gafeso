<?php
require('controles.php');
inicio("manusu");
$idUsuario=$_GET['idUsuario'];
if(Mantenimiento("usuario",array("idUsuario"=>$idUsuario),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>