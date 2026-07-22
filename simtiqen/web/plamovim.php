<?php
	include_once('controles.php');
	inicio("plamovim");
	if(isset($_POST["txtfechai"]))
		$lfechai=$_POST["txtfechai"];
	else
		$lfechai=date("d/m/Y");
	if(isset($_POST["txtfechaf"]))
		$lfechaf=$_POST["txtfechaf"];
	else
		$lfechaf=date("d/m/Y");
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
  	<script type="text/javascript">
      $(function(){
     		$('#txtfechai').datepicker({
   			inline: true
  		});
      $("#txtfechai").datepicker($.datepicker.regional['es']);
      $('#txtfechaf').datepicker({
   			inline: true
  		});
      $("#txtfechaf").datepicker($.datepicker.regional['es']);
        ajusta();
  		});
   	
    </script>
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
	<form name='formconsulta' method='post' action='plamovim.php' id ='formconsulta' >
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
				Seleccione el a&ntilde;o :
			</td>
			<td>
				<select name="cbanno" id="cbanno">
					<?php
						$anno  = date("Y")-3;
						$actual = date("Y");
						for ($i = 0; $i <= 6; $i++) {
							echo '<option value="' . $anno . '"';
							if ($anno == $actual)
							{
								echo " selected ";
							}
							echo '>' . $anno . '</option>';
							$anno++;
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Seleccione el tipo de movimiento :
			</td>
			<td>
				<select id='cbtipo' name='cbtipo'>
					<option value='0'>Seleccione un Tipo de Movimiento</option>
					<?php
						$res=ListaActivos("Vacacion",null);
						for($x=0;$x<count($res);$x++){
							echo "<option value='". $res[$x]["idtipo"]."'";
							echo">".$res[$x]["nombre"]."</option>";
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Seleccione la cantidad de d&iacute;as a aplicar:
			</td>
			<td>
				<input type="number" min="0" id="txtcantdias" name="txtcantdias" class="spinner" value="0">
			</td>
		</tr>
		<tr>
			<td>
				Fecha Inicial:
			</td>
			<td>
				<input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'>
			</td>
		</tr>
		<tr>
			<td>
				Fecha Inicial:
			</td>
			<td>
				<input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'>
			</td>
		</tr>
		<tr>
			<td>
				Razón del Ajuste:
			</td>
			<td>
				<textarea id='txtrazon' name='txtrazon'></textarea>
			</td>
		</tr>
		<tr>
			<td>
				Correo adicional para enviar:
			</td>
			<td>
				<input class="text" type="text" name="txtcorreo" id="txtcorreo" />
			</td>
		</tr>
	    <tr>
			<td>
			</td>
			<td>
				<input type="submit" name="Generar" id="Generar" value="Generar el Ajuste" />
			</td>
		</tr>
		</table>
</form>
</div>
<div id="divdatos">
<?php
   if(isset($_POST["cbanno"])){ 
		if(isset($_POST["cbchofer"])){ $Chofer = $_POST["cbchofer"]; } else { $Chofer = 0; }
		if(isset($_POST["cbanno"])){ $anno = $_POST["cbanno"]; } else { $anno = 0; }
		if(isset($_POST["cbtipo"])){ $IdTipo = $_POST["cbtipo"]; } else { $IdTipo = 0; }
		if(isset($_POST["txtcantdias"])){ $cantdias = $_POST["txtcantdias"]; } else { $cantdias = 0; }
		$Fechaini = fechatoUTC($_POST["txtfechai"]);
		$Fechafin = fechatoUTC($_POST["txtfechaf"]);
		$Razon = $_POST["txtrazon"];
		if ($cantdias != 0){
			if ($anno != 0){
				if ($Chofer != 0){
					if ($IdTipo != 0){
						$res = CreaMovimientoVaca($Chofer,$IdTipo,$cantdias,$anno,$Fechaini,$Fechafin,$Razon);
						if ($res == 0){
							echo "El movimiento no puedo ser creado correctamente";
						}else{
							$xlaboleta = BoletaPlanilla($res);
							echo $xlaboleta;
							$elfiltro = array();
							array_push($elfiltro,"idchofer = ".$Chofer); 
							$consulta = ListaMantenimiento("chofer",$elfiltro,null);
							if (count($consulta)> 0){
								$correo = $consulta[0]["correo"];
								$para      = $correo;
								$titulo = "Boleta de Vacaciones " . $res;
								echo $titulo;
								$mensaje = 'Para firmar la boleta entrar <a href="firmavaca.php?boleta=' . $linea["idmovi"] .'" target="_blank">aqu&iacute;</a>';
								$mensaje .= $xlaboleta;
								$cabeceras = 'From: info@transportesjacoruta655.com' . "\r\n" .
								'Reply-To: info@transportesjacoruta655.com' . "\r\n" .
								'X-Mailer: PHP/' . phpversion(). "\r\n";
								$cabeceras .= 'MIME-Version: 1.0' . "\r\n";
								$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
								if (mail($para, $titulo, $mensaje, $cabeceras))
									$rescorreo="<h1>Se envi&oacute; esta orden al correo ".$correo."</h1>";
								else
									$rescorreo="No se pudo enviar el correo a " . $para;
								echo $rescorreo;
							}
						}
					}
					else{
						echo "Debe digitar el tipo de movimiento";
					}
				}
				else{
					echo "Debe digitar el chofer para este movimiento";
				}
			}
			else{
				echo "Debe digitar el año para generar las vacaciones";
			}
		}
		else{
			echo "Debe los días para generar las vacaciones";
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
