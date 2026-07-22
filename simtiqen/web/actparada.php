<?php
  include_once('controles.php');
  inicio("manpara");
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="css/mantenimiento.css" type="text/css" />
  <script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
  <script src="js/manparada.js" type="text/javascript"></script>
<link rel="stylesheet" href="style.css" type="text/css" />     
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
	$idparada = htmlspecialchars(trim($_POST['idparada']));
	$nomparada = htmlspecialchars(trim($_POST['nomparada']));
	if (isset($_POST['activo'])){
    	$activo = 1;
	}else{
		$activo = 0;
}
	if (isset($_POST['noextra'])){
    	$noextra = 1;
	}else{
		$noextra = 0;
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
	if (Mantenimiento("parada",array("idparada"=>$idparada,"nombre"=>$nomparada,"activo"=>$activo,"noextra"=>$noextra),2) == true){
		echo 'Datos Guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	} 
?>
    <br />
    <br />
	<a href="manparada.php">Regresar al mantenimiento</a>
	</p>
    </div>
</div>
<?php
}else{
	if(isset($_GET['idparada'])){
		$idparada = $_GET['idparada'];
   	$elfiltro = array();
	   array_push($elfiltro,"idparada = ".$idparada); 
      $consulta = ListaMantenimiento("parada",$elfiltro,null);
      if (count($consulta)> 0){
      	$nombre = $consulta[0]["nombre"];
      	$activo = $consulta[0]["activo"];
        $noextra = $consulta[0]["noextra"];
      	$idparada = $consulta[0]["idparada"];
	?>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<form id="frmActualizar" name="frmActualizar" method="post" action="actparada.php" enctype="multipart/form-data" onSubmit="ActualizarDatos(); return false">
    	<input type="hidden" name="idparada" id="idparada" value="<?php echo $idparada ?>" />
        <p>
	  <label>Nombre de la Parada<br />
	  <input class="text" type="text" name="nomparada" id="nomparada" value="<?php echo $nombre ?>" />
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
   if ($noextra == 1){
	   	  echo '<input type="checkbox" name="noextra" id="noextra" value="1" checked> No tomar en cuenta para los extra';
	   }else{
	   	  echo '<input type="checkbox" name="noextra" id="noextra" value="1" > No tomar en cuenta para los extra';
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
         	echo "<a href='manparada.php'>Regresar al mantenimiento</a>";
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
