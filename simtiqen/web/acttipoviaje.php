<?php
  include_once('controles.php');
  inicio("mantipoviaje");
?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
	<title>Sistemas de ventas de tiquetes</title> 
	<link rel="stylesheet" href="css/general.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="stylesheet" href="css/mantenimiento.css" type="text/css" />
	<script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/sfunciones.js"></script>  
	<script src="js/manchofer.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function(){ ajusta();})
	</script>  
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
	Encabezado();
?>
<div class="wrap">
<?php Menu(); ?>

<div id='cuerpo' class='content'>

<!--Inicio /!-->
  <?php
if(isset($_POST['submit'])){
	$idtipoviaje = htmlspecialchars(trim($_POST['idtipoviaje']));
	$destipoviaje = htmlspecialchars(trim($_POST['destipoviaje']));
	if (isset($_POST['activo'])){
    	$activo = 1;
	}else{
		$activo = 0;
	}
	?>
</p>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<br />
	<p align="center">
<?php	
	if (Mantenimiento("tipoviaje",array("idTipoViaje"=>$idtipoviaje,"destipoviaje"=>$destipoviaje,"activo"=>$activo),2) == true){
		echo 'Datos Guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	} 
?>
    <br />
    <br />
	<a href="mantipoviaje.php">Regresar al mantenimiento</a>
	</p>
    </div>
</div>
<?php
}else{
	if(isset($_GET['idtipoviaje'])){
		$idtipoviaje = $_GET['idtipoviaje'];
		$elfiltro = array();
		array_push($elfiltro,"idtipoviaje = ".$idtipoviaje); 
		$consulta = ListaMantenimiento("tipoviaje",$elfiltro,null);
		if (count($consulta)> 0){
			$destipoviaje = $consulta[0]["destipoviaje"];
			$activo = $consulta[0]["activo"];
			$idtipoviaje = $consulta[0]["idtipoviaje"];
	?>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<form id="frmActualizar" name="frmActualizar" method="post" action="acttipoviaje.php" enctype="multipart/form-data" onSubmit="ActualizarDatos(); return false">
    	<input type="hidden" name="idtipoviaje" id="idtipoviaje" value="<?php echo $idtipoviaje ?>" />
        <p>
	  <label>Tipo de Viaje<br />
	  <input class="text" type="text" name="destipoviaje" id="destipoviaje" value="<?php echo $destipoviaje ?>" />
	  </label>
	  </p>
      <br />
     <p>
     <?php
	   if ($activo == 1){
	   	  echo '<input type="checkbox" name="activo" id="activo" value="1" checked> Activo';
	   }else{
	   	  echo '<input type="checkbox" name="activo" id="activo" value="1" > Activo';
	   }
	  ?>
	  <br>
	  </p>
      <br />
     <br />
	  <p>
		<input type="submit" name="submit" id="button" value="Enviar" />
		<label></label>
		<input type="button" name="cancelar" id="cancelar" value="Cancelar" onClick="Cancelar()" />
	  </p>
	  <br />
</form>
    </div>
</div>
	<?php
      }else{
         	echo "<div id='contenedor'>";
         	echo "<div id='tabla' align='center'>";
         	echo "<br>El registro buscado no existe en la base de datos<br><br>";
         	echo "<a href='manchofer.php'>Regresar al mantenimiento</a>";
         	echo "</div>";
         	echo "</div>";
      	}
	}
}
?>
<!--Final /!-->
</div>
</div>
<?php
  Pie();
?>
</body>
</html>
