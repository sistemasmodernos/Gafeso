<?php
require('controles.php');
inicio("mancobra");
$idcobrador=$_GET['idcobrador'];
if(Mantenimiento("cobrador",array("idcobrador"=>$idcobrador),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>