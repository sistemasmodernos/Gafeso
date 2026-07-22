<?php
  include_once('controles.php');
  inicio("manprodu");
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="css/mantenimiento.css" type="text/css" />
  <link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
  <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
  <script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
  <script src="js/manproducto.js" type="text/javascript"></script>
  <script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
  <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
  <script type="text/javascript">     
       $(function(){
            $('#precio').numeric({minValue:1,increment:1,emptyValue: 0});
            ajusta();
			});        
  </script>
<link rel="stylesheet" href="style.css" type="text/css" />     
   <script type="text/javascript" src="js/sfunciones.js"></script>  
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
	$nombre = htmlspecialchars(trim($_POST['nombre']));
	$precio = htmlspecialchars(trim($_POST['precio']));
	$tipo = htmlspecialchars(trim($_POST['tipo']));
	$idProducto = htmlspecialchars(trim($_POST['idProducto']));
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
	if (Mantenimiento("producto",array("idProducto"=>$idProducto,"nombre"=>$nombre,"activo"=>$activo,"precio"=>$precio,"tipo"=>$tipo),2) == true){
		echo 'Datos Guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	} 
?>
    <br />
    <br />
	<a href="manproducto.php">Regresar al mantenimiento</a>
	</p>
    </div>
</div>
<?php
}else{
	if(isset($_GET['idProducto'])){
		$idProducto = $_GET['idProducto'];
   	$elfiltro = array();
	   array_push($elfiltro,"idProducto = ".$idProducto); 
      $consulta = ListaMantenimiento("producto",$elfiltro,null);
      if (count($consulta)> 0){
   		$nombre = $consulta[0]['nombre'];
			$precio = $consulta[0]['precio'];
			$tipo = $consulta[0]['tipo'];
      	$idProducto = $consulta[0]["idProducto"];
      	$activo = $consulta[0]["activo"];
	?>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<form id="frmActualizar" name="frmActualizar" method="post" action="actproducto.php" enctype="multipart/form-data" onSubmit="ActualizarDatos(); return false">
    	<input type="hidden" name="idProducto" id="idProducto" value="<?php echo $idProducto ?>" />
        <p>
	  <label>Nombre del Producto<br />
	  <input class="text" type="text" name="nombre" id="nombre" value="<?php echo $nombre ?>" />
	  </label>
	  </p>
      <br />
    <p>
    <label>Precio del Producto<br />
    <input class="numerico" type="text" name="precio" id="precio" value="<?php echo $precio ?>" />
    </label>
	 </p>
 	 <br>
    <p>
    <label>Tipo del Producto<br />
    <?php
      if ($tipo == 1){
      	echo '<input type="radio" name="tipo" value="1" checked>Tiquete<br><input type="radio" name="tipo" value="2" >Encomienda<br>';
      }else{
      	echo '<input type="radio" name="tipo" value="1" >Tiquete<br><input type="radio" name="tipo" value="2" checked>Encomienda<br>';
      }
    ?> 
    </label>
	 </p>
 	<br>
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
         	echo "<a href='manproducto.php'>Regresar al mantenimiento</a>";
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
