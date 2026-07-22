<?php
require('controles.php');
inicio("manempresa");
$idautoriza=$_GET['idautoriza'];
$xEmpresa = new Empresa();
if($xEmpresa->EliminaAutoriza($idautoriza) == true){
	echo "Registro eliminado correctamente";
}else{
	echo "No pudo ser borrado el registro";
}
?>