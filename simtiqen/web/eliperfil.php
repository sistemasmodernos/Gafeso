<?php
require('controles.php');
inicio("manperfil");
$idperfil = $_GET['idperfil'];
if(Mantenimiento("perfil",array("idperfil"=>$idperfil),3) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>