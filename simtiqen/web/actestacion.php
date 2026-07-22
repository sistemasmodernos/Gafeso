<?php
  include_once('controles.php');
  inicio("manesta");
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="css/mantenimiento.css" type="text/css" />
  <script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
  <script src="js/manestacion.js" type="text/javascript"></script>
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
	$idestacion = htmlspecialchars(trim($_POST['idestacion']));
	$nomestacion = htmlspecialchars(trim($_POST['nomestacion']));
	if (isset($_POST['activo'])){
    	$activo = 1;
	}else{
		$activo = 0;
	}
	$idparada = htmlspecialchars(trim($_POST['idparada']));?>
</p>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<br />
	<p align="center">
<?php	
	if (Mantenimiento("estacion",array("idestacion"=>$idestacion,"nombre"=>$nomestacion,"activo"=>$activo,"parada"=>$idparada),2) == true){
		echo 'Datos Guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	} 
?>
    <br />
    <br />
	<a href="manestacion.php">Regresar al mantenimiento</a>
	</p>
    </div>
</div>
<?php
}else{
	if(isset($_GET['idestacion'])){
		$idestacion = $_GET['idestacion'];
   	$elfiltro = array();
	   array_push($elfiltro,"idestacion = ".$idestacion); 
      $consulta = ListaMantenimiento("estacion",$elfiltro,null);
      if (count($consulta)> 0){
      	$nombre = $consulta[0]["nombre"];
      	$activo = $consulta[0]["activo"];
      	$idparada = $consulta[0]["idparada"];
	?>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<form id="frmActualizar" name="frmActualizar" method="post" action="actestacion.php" enctype="multipart/form-data" onSubmit="ActualizarDatos(); return false">
    	<input type="hidden" name="idestacion" id="idestacion" value="<?php echo $idestacion ?>" />
        <p>
	  <label>Nombre de la Estaci&oacute;n<br />
	  <input class="text" type="text" name="nomestacion" id="nomestacion" value="<?php echo $nombre ?>" />
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
        <p>
	  <label>Parada<br /> </label>

	  <select id='idparada' name='idparada'>
     <?php
        $res=ListaActivos("Parada",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idparada"]."'";
           if ($res[$x]["idparada"] == $idparada){
           	  echo " selected ";
           	}
           echo">".$res[$x]["nombre"]."</option>";
        }
     ?>	  
     </select>
	  </p>
     <p>
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
         	echo "<a href='manestacion.php'>Regresar al mantenimiento</a>";
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
