<?php
  include_once('controles.php');
  inicio("platodo");
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
	<form name='formconsulta' method='post' action='platodo.php' id ='formconsulta' >
		<table>
		<tr>
			<td>
				Seleccione el a&ntilde;o para las vacaciones :
			</td>
			<td>
				<select name="cbanno" id="cbanno">
					<?php
						$anno  = date("Y");
						for ($i = 0; $i <= 2; $i++) {
							echo '<option value="' . $anno . '">' . $anno . '</option>';
							$anno++;
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Seleccione la cantidad de d&iacute;as a aplicar a todos los choferes:
			</td>
			<td>
				<input type="number" min="1" id="txtcantdias" name="txtcantdias" class="spinner" value="12">
			</td>
		</tr>
	    <tr>
			<td>
			</td>
			<td>
				<input type="submit" name="Generar" id="Generar" value="Aplicar días a todos los choferes" />
			</td>
		</tr>
		</table>
</form>
<?php
   if(isset($_POST["cbanno"])){ 
		if(isset($_POST["txtcantdias"])){ $cantdias = $_POST["txtcantdias"]; } else { $cantdias = 0; }
		if(isset($_POST["cbanno"])){ $anno = $_POST["cbanno"]; } else { $anno = "0"; }
		if ($cantdias != 0){
			if ($anno != 0){
				echo CreaVacionesGlobales($cantdias,$anno);
			}
			else{
				echo "Debe digitar el año para generar las vacaciones";
			}
		}
		else{
			echo "Debe los días para generar las vacaciones";
		}
   }
   else{
	    $res=ListaActivos("Chofer",null);
		echo "Se crearán vacaciones para los siguientes choferes: <ul>";
        for($x=0;$x<count($res);$x++){
            echo "<li>" . $res[$x]["Nombre"] . "</li>";
        }
		echo "<ul>";
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
