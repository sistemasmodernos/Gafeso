<?php
  include_once('controles.php');
  inicio("busenco");
  
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <link type="text/css" href="css/encomiendas.css" rel="stylesheet" />               
        <link type="text/css" href="css/buses.css" rel="stylesheet" />                
        <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
        <script type="text/javascript" src="js/buscarenco.js"></script>
        <script>
        $(document).ready(ajusta);
        </script>
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
   Encabezado();
   
?>
<div class="wrap">
<?php Menu(); ?>
	<div id='cuerpo' class='content'>
	<!--- INICIA PANTALLA	-->
	<form name='formconsulta' method='post' action='buscarenco.php' id ='formconsulta' onsubmit="return validacion()" >
		<table>
		<tr>
			<td>
				Número de la encomienda :
			</td>
			<td>
				<input type='text' name='txtfactura' id='txtfactura' value='<?php if(isset($_POST["txtfactura"])){ echo $_POST["txtfactura"]; } else { echo ""; } ?>' placeholder="Número de la encomienda">
			</td>
		</tr>
		<tr>
			<td>
				Remitente :
			</td>
			<td>
				<input type='text' name='txtremi' id='txtremi' value='<?php if(isset($_POST["txtremi"])){ echo $_POST["txtremi"]; } else { echo ""; } ?>' placeholder="Nombre del remitente">
			</td>
		</tr>
		<tr>
			<td>
				Cédula del Remitente :
			</td>
			<td>
				<input type='text' name='txtcedr' id='txtcedr' value='<?php if(isset($_POST["txtcedr"])){ echo $_POST["txtcedr"]; } else { echo ""; } ?>' placeholder="Cédula del remitente">
			</td>
		</tr>
	    <tr>
			<td>
				Destinatario :
			</td>
			<td>
				<input type='text' name='txtdesti' id='txtdesti' value='<?php if(isset($_POST["txtdesti"])){ echo $_POST["txtdesti"]; } else { echo ""; } ?>' placeholder="Destinatario de la encomienda">
			</td>
		</tr>
	    <tr>
			<td>
				Cédula del Destinatario :
			</td>
			<td>
				<input type='text' name='txtcedd' id='txtcedd' value='<?php if(isset($_POST["txtcedd"])){ echo $_POST["txtcedd"]; } else { echo ""; } ?>' placeholder="Cédula del Destinatario">
			</td>
		</tr>
	    <tr>
			<td>
				<span class="txtform">Fecha Inicial: </span>
			</td>
			<td>
				<input type='text' name='txtfecha' id='txtfecha' class='fecha' value='<?php if(isset($_POST["txtfecha"])){ echo $_POST["txtfecha"]; } else { echo ""; } ?>'>
			</td>
		</tr>
	    <tr>
			<td>
				<span class="txtform">Fecha Final: </span>
			</td>
			<td>
				<input type='text' name='txtfechaf' id='txtfechaf' class='fecha' value='<?php if(isset($_POST["txtfechaf"])){ echo $_POST["txtfechaf"]; } else { echo ""; } ?>'>
			</td>
		</tr>
	    <tr>
			<td>
				Enviar el reporte a Excel:
			</td>
			<td>
				<input type="checkbox" name="excel" id="excel" value="1"  />
			</td>
		</tr>
	    <tr>
			<td>
			</td>
			<td>
				<input type="submit" name="Consulta" id="Consulta" value="Buscar Encomiendas" />
			</td>
		</tr>
		</table>
</form>
<?php
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
   if(isset($_GET["id"])){ 
		$laconsulta = new Encomienda();
		echo $laconsulta->HTML($_GET["id"]);
   }
?>
<!-- Finaliza Pantalla -->
	</div>
</div>
<?php
  Pie();
?>

<input type='hidden' id='calenhora'>
</body>
</html>
