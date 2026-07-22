<?php
  include_once('controles.php');
  inicio("mancobra");
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
    <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/mantenimiento.css" type="text/css" />
  <script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
  <script src="js/mancobrador.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/sfunciones.js"></script>  
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
	$idcobrador = htmlspecialchars(trim($_POST['idcobrador']));
	$nomcobrador = htmlspecialchars(trim($_POST['nomcobrador']));
	$cedula = htmlspecialchars(trim($_POST['cedula']));
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
	if (Mantenimiento("cobrador",array("idcobrador"=>$idcobrador,"nombre"=>$nomcobrador,"cedula"=>$cedula,"activo"=>$activo),2) == true){
		echo 'Datos Guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	} 
?>
    <br />
    <br />
	<a href="mancobrador.php">Regresar al mantenimiento</a>
	</p>
    </div>
</div>
<?php
}else{
	if(isset($_GET['idcobrador'])){
		$idcobrador = $_GET['idcobrador'];
   	$elfiltro = array();
	   array_push($elfiltro,"idcobrador = ".$idcobrador); 
      $consulta = ListaMantenimiento("cobrador",$elfiltro,null);
      if (count($consulta)> 0){
      	$nombre = $consulta[0]["nombre"];
      	$activo = $consulta[0]["activo"];
      	$cedula = $consulta[0]["cedula"];
      	$idcobrador = $consulta[0]["idcobrador"];
	?>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<form id="frmActualizar" name="frmActualizar" method="post" action="actcobrador.php" enctype="multipart/form-data" onSubmit="ActualizarDatos(); return false">
    	<input type="hidden" name="idcobrador" id="idcobrador" value="<?php echo $idcobrador ?>" />
        <p>
	  <label>Nombre del Cobrador<br />
	  <input class="text" type="text" name="nomcobrador" id="nomcobrador" value="<?php echo $nombre ?>" />
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
         	echo "<a href='mancobrador.php'>Regresar al mantenimiento</a>";
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
