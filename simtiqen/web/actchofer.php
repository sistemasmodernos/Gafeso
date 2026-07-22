<?php
  include_once('controles.php');
  inicio("manchofer");
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
	$idchofer = htmlspecialchars(trim($_POST['idchofer']));
	$nomchofer = htmlspecialchars(trim($_POST['nomchofer']));
	$cedula = htmlspecialchars(trim($_POST['cedula']));
	$correo = htmlspecialchars(trim($_POST['correo']));
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
	if (Mantenimiento("chofer",array("idchofer"=>$idchofer,"nombre"=>$nomchofer,"cedula"=>$cedula,"activo"=>$activo,"correo"=>$correo),2) == true){
		echo 'Datos Guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	} 
?>
    <br />
    <br />
	<a href="manchofer.php">Regresar al mantenimiento</a>
	</p>
    </div>
</div>
<?php
}else{
	if(isset($_GET['idchofer'])){
		$idchofer = $_GET['idchofer'];
   	$elfiltro = array();
	   array_push($elfiltro,"idchofer = ".$idchofer); 
      $consulta = ListaMantenimiento("chofer",$elfiltro,null);
      if (count($consulta)> 0){
      	$nombre = $consulta[0]["Nombre"];
      	$activo = $consulta[0]["activo"];
      	$cedula = $consulta[0]["cedula"];
      	$correo = $consulta[0]["correo"];
      	$idchofer = $consulta[0]["idchofer"];
	?>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<form id="frmActualizar" name="frmActualizar" method="post" action="actchofer.php" enctype="multipart/form-data" onSubmit="ActualizarDatos(); return false">
    	<input type="hidden" name="idchofer" id="idchofer" value="<?php echo $idchofer ?>" />
        <p>
	  <label>Nombre del Chofer<br />
	  <input class="text" type="text" name="nomchofer" id="nomchofer" value="<?php echo $nombre ?>" />
	  </label>
	  </p>
      <br />
        <p>
	  <label>C&eacute;dula<br />
	  <input class="text" type="text" name="cedula" id="cedula" value="<?php echo $cedula ?>" />
	  </label>
	  </p>
      <br />
        <p>
	  <label>Correo<br />
	  <input class="text" type="text" name="correo" id="correo" value="<?php echo $correo ?>" />
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
