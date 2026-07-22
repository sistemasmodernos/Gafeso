<?php
  include_once('controles.php');
  inicio("manperfil");
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link type="text/css" href="css/mantenimiento.css" rel="stylesheet" /> 
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
    if (Mantenimiento("perfil",array("nombre"=>$nombre,"activo"=>$activo),1) == true){
		echo 'Datos guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	}
?>
	<a href="manperfil.php">Regresar al mantenimiento</a>
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
    <form id="frmNuevo" name="frmNuevo" method="post" action="newperfil.php" enctype="multipart/form-data" onSubmit="GrabarDatos(); return false">
    <p>
    <label>Nombre del Perfil<br />
    <input class="text" type="text" name="nombre" id="nombre" />
    </label>
	</p>
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
