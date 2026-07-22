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
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
   Encabezado();
   
?>
<div class="wrap">
<?php Menu(); ?>
	<div id='cuerpo' class='content'>
	<!--- INICIA PANTALLA	-->
	<form name='formconsulta' method='post' action='excenco.php' target='_blank' id ='formconsulta' onsubmit="return validacion()" >
		<table>
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
				Formato del reporte:
			</td>
			<td>
				<input type="radio" name="formato" value="1" checked >Resumido
				<input type="radio" name="formato" value="2">Detallado
			</td>
		</tr>
	    <tr>
			<td>
			</td>
			<td>
				<input type="submit" name="Excel" id="Excel" value="Generar Excel" />
			</td>
		</tr>
		</table>
</form>
<?php
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
<!-- Finaliza Pantalla -->
	</div>
</div>
<?php
  Pie();
?>

<input type='hidden' id='calenhora'>
</body>
</html>
