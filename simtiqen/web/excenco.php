<?
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=busqueda.xls");
header("Pragma: no-cache");
header("Expires: 0");?>

<?php
    include_once('controles.php');
	if(isset($_POST["Consulta"])){ 
		if(isset($_POST["txtfactura"])){ $txtfactura = $_POST["txtfactura"]; } else { $txtfactura = ""; }
		if(isset($_POST["txtremi"])){ $txtremi = $_POST["txtremi"]; } else { $txtremi = ""; }
		if(isset($_POST["txtcedr"])){ $txtcedr = $_POST["txtcedr"]; } else { $txtcedr = ""; }
		if(isset($_POST["txtdesti"])){ $txtdesti = $_POST["txtdesti"]; } else { $txtdesti = ""; }
		if(isset($_POST["txtcedd"])){ $txtcedd = $_POST["txtcedd"]; } else { $txtcedd = ""; }
		if(isset($_POST["txtfecha"])){ $txtfecha = $_POST["txtfecha"]; } else { $txtfecha = ""; }
		if(isset($_POST["txtfechaf"])){ $txtfechaf = $_POST["txtfechaf"]; } else { $txtfechaf = ""; }
		$laconsulta = new Encomienda();
		echo $laconsulta->Buscar($txtfactura,$txtremi,$txtcedr,$txtdesti,$txtcedd,$txtfecha,$txtfechaf);
	}
	if(isset($_POST["Excel"])){ 
		if(isset($_POST["txtremi"])){ $txtremi = $_POST["txtremi"]; } else { $txtremi = ""; }
		if(isset($_POST["txtcedr"])){ $txtcedr = $_POST["txtcedr"]; } else { $txtcedr = ""; }
		if(isset($_POST["txtfecha"])){ $txtfecha = $_POST["txtfecha"]; } else { $txtfecha = ""; }
		if(isset($_POST["txtfechaf"])){ $txtfechaf = $_POST["txtfechaf"]; } else { $txtfechaf = ""; }
		if(isset($_POST["formato"])){ $formato = $_POST["formato"]; } else { $formato = "1"; }
		$laconsulta = new Encomienda();
		echo $laconsulta->Excel($txtremi,$txtcedr,$txtfecha,$txtfechaf,$formato);
	}
?>