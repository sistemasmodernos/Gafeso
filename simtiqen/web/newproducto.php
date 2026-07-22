<?php
  include_once('controles.php');
  inicio("manprodu");
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
  <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
  <link type="text/css" href="css/mantenimiento.css" rel="stylesheet" />       
  <script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
  <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
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

<?php
if(isset($_POST['submit'])){
	$nombre = htmlspecialchars(trim($_POST['nombre']));
	$precio = htmlspecialchars(trim($_POST['precio']));
	$tipo = htmlspecialchars(trim($_POST['tipo']));
	if (isset($_POST['activo'])){
    	$activo = 1;
	}else{
		$activo = 0;
	}
?>
	<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<br />
	<p align="center">
<?php	
    if (Mantenimiento("Producto",array("nombre"=>$nombre,"activo"=>$activo,"precio"=>$precio,"tipo"=>$tipo),1) == true){
		echo 'Datos guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	}
?>
	<a href="manproducto.php">Regresar al mantenimiento</a>
	</p>
    </div>
   </div>
<?php
}else{
?>
    <div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<br />
    <form id="frmNuevo" name="frmNuevo" method="post" action="newproducto.php" enctype="multipart/form-data" onSubmit="GrabarDatos(); return false">
    <p>
    <label>Nombre del Producto<br />
    <input class="text" type="text" name="nombre" id="nombre" />
    </label>
	</p>
 	<br>
    <p>
    <label>Precio del Producto<br />
    <input type="text" name="precio" id="precio" class='numerico'>
    </label>
	</p>
 	<br>
   <p>
   <label>Tipo del Producto<br />
   <input type="radio" name="tipo" value="1" checked>Tiquete<br>
   <input type="radio" name="tipo" value="2" >Encomienda<br> 	
   </label>
	</p>
 	<br>
   <p>
   <input type="checkbox" name="activo" id="activo" value="1" checked> Activo<br>
  </p>
  <p>
    <br />
    <input type="submit" name="submit" id="button" value="Enviar" />
    <label></label>
    <input type="button" class="cancelar" name="cancelar" id="cancelar" value="Cancelar" onClick="Cancelar()" />
  </p>
</form>
    </div>
</div>
</div>
<?php
}
?>
</div>
<?php
  Pie();
?>
</body>
</html>
