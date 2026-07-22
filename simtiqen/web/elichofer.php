<?php
require('controles.php');
inicio("manchofer");
$idchofer=$_GET['idchofer'];
if(Mantenimiento("chofer",array("idchofer"=>$idchofer),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>