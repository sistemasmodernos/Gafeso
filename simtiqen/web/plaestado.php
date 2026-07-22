<?php
	include_once('controles.php');
	inicio("plaestado");
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
    <link type="text/css" href="css/planilla.css" rel="stylesheet" />                
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
	<div id="controles">
	<!--- INICIA PANTALLA	-->
	<form name='formconsulta' method='post' action='plaestado.php' id ='formconsulta' >
		<table>
		<tr>
			<td>
				Seleccione el chofer :
			</td>
			<td>
				<select id='cbchofer' name='cbchofer'>
					<option value='0'>Seleccione un Chofer</option>
					<?php
						$res=ListaActivos("Chofer",null);
						for($x=0;$x<count($res);$x++){
							echo "<option value='". $res[$x]["idchofer"]."'";
							echo">".$res[$x]["Nombre"]."</option>";
						}
					?>
				</select>
			</td>
		</tr>
	    <tr>
			<td>
			</td>
			<td>
				<input type="submit" name="Generar" id="Generar" value="Generar el Reporte" />
			</td>
		</tr>
		</table>
</form>
</div>
<div id="divdatos">
<?php
   if(isset($_POST["cbchofer"])){ 
		if(isset($_POST["cbchofer"])){ $Chofer = $_POST["cbchofer"]; } else { $Chofer = 0; }
		if ($Chofer != 0){
			echo VacaEstadodeCuenta($Chofer);
		}
		else{
			echo "Debe digitar el chofer para este movimiento";
		}
   }
?>
</div>
<!-- Finaliza Pantalla -->
	</div>
</div>
<?php
  Pie();
?>

<input type='hidden' id='calenhora'>
</body>
</html>
