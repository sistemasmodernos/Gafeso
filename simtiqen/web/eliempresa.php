<?php
require('controles.php');
inicio("manempresa");
$idempresa=$_GET['idempresa'];
if(Mantenimiento("empresa",array("idempresa"=>$idempresa),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>