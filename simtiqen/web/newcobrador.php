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

<?php
if(isset($_POST['submit'])){
	$nomcobrador = htmlspecialchars(trim($_POST['nomcobrador']));
	$cedula = htmlspecialchars(trim($_POST['cedula']));
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
    if (Mantenimiento("cobrador",array("nombre"=>$nomcobrador,"cedula"=>$cedula,"activo"=>$activo),1) == true){
		echo 'Datos guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	}
?>
	<a href="mancobrador.php">Regresar al mantenimiento</a>
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
    <form id="frmNuevo" name="frmNuevo" method="post" action="newcobrador.php" enctype="multipart/form-data" onSubmit="GrabarDatos(); return false">
    <p>
    <label>Nombre del Cobrador<br />
    <input class="text" type="text" name="nomcobrador" id="nomcobrador" />
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
