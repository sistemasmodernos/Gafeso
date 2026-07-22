
<?php
	include_once('controles.php');
	include_once('lib/nucleo.php');
	include_once('lib/utiles.php');
	inicio("prohora");
	$elerror="";
	if(isset($_GET["do"])){
		// esto se ejecuta cuando se esta guardando
	    if($_GET["do"]=="save"){
	        if(isset($_POST["ckextra"]))
				$extra=$_POST["ckextra"];
	        else
				$extra=0;
			if(isset($_POST["cknoweb"]))
				$noweb=1;
			else
				$noweb=0;            
			if($_POST["txthora"]=="")
				$elerror="No puede dejar la hora en blanco";
			else {
				$parametros=array(
					"idviaje"=>$_POST["txtidviaje"],
					"idruta"=>$_POST["cbruta"],
					"fecha"=>fechatoUTC($_POST["txtfecha"]),
					"horas"=>array($_POST["cbestacion"].""=>$_POST["txthora"]),
					"idchofer"=>$_POST["cbchofer"],
					"idcobrador"=>$_POST["cbcobrador"],
					"idtipoviaje"=>$_POST["cbtipoviaje"],
					"idbus"=>$_POST["cbbus"],
					"estadi"=>$_POST["cbestacion"],
					"extra"=>$extra,
					"noweb"=>$noweb
				);
				$res= Mantenimiento("Viaje",$parametros,1);
			}
	    }
	    if($_GET["do"]=="del"){
			if(isset($_POST["ckextra"]))
				$extra=1;
			else
				$extra=0;
			if(isset($_POST["cknoweb"]))
				$noweb=1;
			else
				$noweb=0;
			$parametros=array(
				"idviaje"=>$_POST["txtidviaje"],
	            "idruta"=>$_POST["cbruta"],
	            "fecha"=>fechatoUTC($_POST["txtfecha"]),
	            "horas"=>array($_POST["cbestacion"].""=>$_POST["txthora"]),
	            "idchofer"=>$_POST["cbchofer"],
	            "idcobrador"=>$_POST["cbcobrador"],
	            "idtipoviaje"=>$_POST["cbtipoviaje"],
	            "idbus"=>$_POST["cbbus"],
	            "estadi"=>$_POST["cbestacion"],
	            "extra"=>$extra,
	            "noweb"=>$noweb
	        );
			$res= Mantenimiento("Viaje",$parametros,3);
	    }      
	}
	if(isset($_POST["cbruta"]))
		$pruta=$_POST["cbruta"];
	else
		$pruta=$_SESSION["parametros"]["rutadefault"];
	if(isset($_POST["cbestacion"]))
		$pestacion=$_POST["cbestacion"];
	else
		$pestacion=$_SESSION["parametros"]["estaciondefault"];
	$lestacion=$pestacion;
	$lruta=$pruta;
	$lbus="";
	$lchofer="";
	$lcobrador="";
	$ltipoviaje="";
	$lidviaje="";
	$lextra=0;
	$lnoweb=0;
	$lhora="";
	$lfecha="";
   $lvendido=0;
	if(isset($_GET["fecha" ]))
		$inicio=$_GET["fecha" ];
	else
		$inicio = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-7,   date("Y")));
	//$inicio=date("Y-m-d",time());
	if(isset($_GET["id"])){
		//significa que hay que cargar los datos del viaje
		$elfiltro = array();
		$lidviaje=$_GET["id"];
		array_push($elfiltro,"v.idviaje = ".$lidviaje); 
		$consulta = ListaMantenimiento("Viaje",$elfiltro,null);
		if (count($consulta)> 0){
			$lfecha =fechatoNormal($consulta[0]["fecha"]);
			$lruta = $consulta[0]["idruta"];
			$lestacion=$consulta[0]["estadi"];
			$lhora = $consulta[0]["hora"];
			$lbus= $consulta[0]["idbus"];
			$lcobrador=$consulta[0]["idcobrador"];
			$ltipoviaje=$consulta[0]["idtipoviaje"];
			$lchofer=$consulta[0]["idchofer"];
			$lextra = $consulta[0]["extra"];
			$lnoweb = $consulta[0]["noweb"];
			//echo "(" . $ltipoviaje . ") (" . $consulta[0]["idtipoviaje"] . ")";
		}
		$lvendido=VendidosxViaje($lidviaje);
	}
	$fin = date('Y-m-d',strtotime($inicio." + 330 days"));
	$viajes=Viajes($pruta,$pestacion,$inicio,$fin);
?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
	<title>Sistemas de ventas de tiquetes</title> 
	<link rel="stylesheet" href="css/general.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />  
	<link rel="stylesheet" href="css/horarios.css" type="text/css" />
	<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
	<link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
	<style type='text/css'>
		#calendar {
			width: 500px;
			margin: 0 auto;
	        font-size:120%;
		}
	</style>
	<script type="text/javascript" src="js/sfunciones.js"></script>
	<script type="text/javascript" src="js/horarios.js"></script>
	<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>        
	<script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
	<script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
	<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.css' />
	<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.print.css' media='print' />
	<script type='text/javascript' src='fullcalendar/fullcalendar.min.js'></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			//$("#txthora").timeEntry({show24Hours: true});
			$('#calendar').fullCalendar({
				editable: true,
				events: [
				<?php
					for($x=0;$x<count($viajes);$x++){
						$fe = strtotime(substr($viajes[$x]["fecha"],0,10)." ".$viajes[$x]["hora"]);
                                                if($viajes[$x]["extra"]=='1')
						  $vextra=" extra ";
						else
						  $vextra="";

						echo "{
							id: ".$viajes[$x]["idviaje"].",
							title: '".$viajes[$x]["hora"]." ".$viajes[$x]["placa"]." ".$viajes[$x]["vendidos"]."/".$viajes[$x]["cantpas"].$vextra."',
							start: new Date(".date("Y",$fe).", ".date("m",$fe)."-1, ".date("d",$fe).",".date("G",$fe).",".date("i",$fe)."),
							backgroundColor:'".$viajes[$x]["color"]."',
							url: 'horarios.php?id=".$viajes[$x]["idviaje"]."'
						},
						";
					}
				?>
				]
			});
			$('#txtfecha').datepicker({
				inline: true
			});
			$('#txtfecha').datepicker('option', {dateFormat: 'dd/mm/yy'});                
	        $("#txtfecha").datepicker($.datepicker.regional['es']);
	        ajusta();
	        $('#txtfechai').datepicker({
				inline: true
			});
			$('#txtfechai').datepicker('option', {dateFormat: 'dd/mm/yy'});       
	        $("#txtfechai").datepicker($.datepicker.regional['es']);
			$('#txtfechaf').datepicker({
				inline: true
			});
	        $("#txtfechaf").datepicker($.datepicker.regional['es']);

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
		<div style='float:right;'><div id='calendar'></div></div>
		<form id='formhora' name='formhora' action='horarios.php' method='post'>
			<table>
    <tr>
    <td>
      <?php
      if($lvendido>0)
        echo "<span style='background-color:yellow;font-weight:bold;font-size:110%;padding:5px;margin:5px;'>Atenci&oacute;n 
          este viaje ya tiene  ".$lvendido." tiquetes vendidos </span>";
      ?>
   </td>
   </tr>


				<tr> <td> Estaci&oacute;n: </td></tr>
				<tr>
					<td>
						<select id='cbestacion' name='cbestacion' onchange='cambiaestacion(this.value);'>
							<?php
								$res=ListaActivos("Estacion",null);
								for($x=0;$x<count($res);$x++){
									echo "<option value='". $res[$x]["idestacion"]."'";
									if($res[$x]["idestacion"]==$lestacion)
										echo " selected ";
									echo">".$res[$x]["nombre"]."</option>";
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td> Ruta:</td>
				</tr>
				<tr>
					<td><select id='cbruta' name='cbruta' onchange='cambiaestacion(0);'>
						<?php
							$res=ListaActivos("Ruta",null);
							for($x=0;$x<count($res);$x++){
								echo "<option value='". $res[$x]["idruta"]."'";
								if($res[$x]["idruta"]==$lruta)
									echo " selected ";
								echo">".$res[$x]["nombre"]."</option>";
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Fecha del Viaje<td>
				</tr>
				<tr>
					<td><input type='text' name='txtfecha' id='txtfecha' class='fecha' value='<?php echo $lfecha; ?>'></td>
				</tr>
				<tr>
					<td>Hora</td>
				</tr>
				<tr>
					<td><input type='time' name='txthora' id='txthora' value='<?php echo $lhora; ?>'></td>
				</tr>
				<tr>
					<td>Autobus</td>
				</tr>
				<tr>
					<td>
						<select id='cbbus' name='cbbus' onchange='traechofer();'>
						<?php
							$res=ListaActivos("Bus",null);
							for($x=0;$x<count($res);$x++){
								echo "<option value='". $res[$x]["idbus"]."'";
								if($res[$x]["idbus"]==$lbus)
									echo " selected ";
								echo">".$res[$x]["placa"]."</option>";
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Chofer</td>
				</tr>
				<tr>
					<td>
						<select id='cbchofer' name='cbchofer' style='width:200px;'>
						<?php
							$res=ListaActivos("Chofer",null);
							for($x=0;$x<count($res);$x++){
								echo "<option value='". $res[$x]["idchofer"]."'";
								if($res[$x]["idchofer"]==$lchofer)
									echo " selected ";
								echo">".$res[$x]["Nombre"]."</option>";
							}	
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Cobrador</td>
				</tr>
				<tr>
					<td>
						<select id='cbcobrador' name='cbcobrador' >
						<?php
							$res=ListaActivos("Cobrador",null);
							for($x=0;$x<count($res);$x++){
								echo "<option value='". $res[$x]["idcobrador"]."'";
								if($res[$x]["idcobrador"]==$lcobrador)
									echo " selected ";
								echo">".$res[$x]["nombre"]."</option>";
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Tipo de Viaje</td>
				</tr>
				<tr>
					<td>
						<select id='cbtipoviaje' name='cbtipoviaje' >
						<?php
							$res=ListaActivos("TipoViaje",null);
							for($x=0;$x<count($res);$x++){
								echo "<option value='". $res[$x]["idtipoviaje"]."'";
								if($res[$x]["idtipoviaje"]==$ltipoviaje)
									echo " selected ";
								echo">".$res[$x]["destipoviaje"]."</option>";
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="ckextra" id='ckextra' value='1'  <?php if($lextra==1) echo "checked"; ?> >Extra</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="cknoweb" id='cknoweb' value='1'  <?php if($lnoweb ==1) echo "checked"; ?> >No presentar en web</td>
				</tr>    
				<tr>
					<td><div id="cuadroguardar"><input type="submit" value="Guardar"  onclick='guardar();' ></div></td>
				</tr>
			</table>
			<p><a href="#"  onclick="eliminar();" >Eliminar horario</a><br/>
			<a href="horarios.php">Limpiar</a></p>
			<p><h1><?php echo $elerror; ?></h1></p>
			<input type='hidden' name='txtidviaje' id='txtidviaje' value='<?php echo $lidviaje; ?>'  >
		</form>
		<div id='divclonar' style='background-color:#cececc;margin:20px;clear:both;border-style:solid;boder-width:1px;padding:5px;'>
			<form id='formclonar' name='formclonar' action='clonar.php' method='post'> 
				<p style='font-size:130%;'>Creaci&oacute;n autom&aacute;tica de horarios </p>
				Desde:<input type='text' id='txtfechai' name='txtfechai'><br/>  Hasta:<input type='text' id='txtfechaf' name='txtfechaf'> <br/>
				<input type='submit' value='Crear Horarios estandar ' ></input>
			</form>
		</div> 
	</div>
	</div>
	<?php
		Pie();
	?>
</body>
</html>
