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
  <script src="js/manchofer.js" type="text/javascript"></script>
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
	$nomchofer = htmlspecialchars(trim($_POST['nomchofer']));
	$cedula = htmlspecialchars(trim($_POST['cedula']));
	$correo = htmlspecialchars(trim($_POST['correo']));
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
    if (Mantenimiento("chofer",array("nombre"=>$nomchofer,"cedula"=>$cedula,"activo"=>$activo,"correo"=>$correo),1) == true){
		echo 'Datos guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	}
?>
	<a href="manchofer.php">Regresar al mantenimiento</a>
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
    <form id="frmNuevo" name="frmNuevo" method="post" action="newchofer.php" enctype="multipart/form-data" onSubmit="GrabarDatos(); return false">
    <p>
    <label>Nombre del Chofer<br />
    <input class="text" type="text" name="nomchofer" id="nomchofer" />
    </label>
	</p>
 	<br>
    <p>
    <label>C&eacute;dula<br />
    <input class="text" type="text" name="cedula" id="cedula" />
    </label>
	</p>
 	<br>
    <p>
    <label>Correo<br />
    <input class="text" type="text" name="correo" id="correo" />
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
<?php
}
?>
</div>
</div>
<?php
  Pie();
?>
</body>
</html>
