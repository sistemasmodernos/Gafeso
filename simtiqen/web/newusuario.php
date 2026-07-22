<?php
  include_once('controles.php');
  inicio("manusu");
  
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="css/mantenimiento.css" type="text/css" />
  <script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
  <script src="js/manusuario.js" type="text/javascript"></script>
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
	$nombrelargo = htmlspecialchars(trim($_POST['nombrelargo']));
	$email = htmlspecialchars(trim($_POST['email']));
	if (isset($_POST['activo'])){
    	$activo = 1;
	}else{
		$activo = 0;
	}
	$idimpresora = htmlspecialchars(trim($_POST['idimpresora']));
	$idperfil = htmlspecialchars(trim($_POST['idperfil']));
	$idruta = htmlspecialchars(trim($_POST['idruta']));
	$idestacion = htmlspecialchars(trim($_POST['idestacion']));
?>
	<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<br />
	<p align="center">
<?php	
    if (Mantenimiento("usuario",array("nombre"=>$nombre,"nombrelargo"=>$nombrelargo,"email"=>$email,"activo"=>$activo,"idimpresora"=>$idimpresora,"idruta"=>$idruta,"idestacion"=>$idestacion,"idperfil"=>$idperfil),1) == true){
		echo 'Datos guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	}
?>
	<a href="manusuario.php">Regresar al mantenimiento</a>
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
    <form id="frmNuevo" name="frmNuevo" method="post" action="newusuario.php" enctype="multipart/form-data" onSubmit="GrabarDatos(); return false">
   <p>
   <label>C&oacute;digo de Usuario<br />
   <input class="text" type="text" name="nombre" id="nombre" />
   </label>
	</p>
 	<br>

   <p>
   <label>Nombre del Usuario<br />
   <input class="text" type="text" name="nombrelargo" id="nombrelargo" />
   </label>
	</p>
 	<br>
   <p>
   <label>Correo Electr&oacute;nico<br />
   <input class="text" type="text" name="email" id="email" />
   </label>
	</p>
 	<br>
   <p>
   <input type="checkbox" name="activo" id="activo" value="1" checked> Activo<br>
	</p>
   <br />
   <p>
	<label>Perfil<br /> </label>
   <select id='idperfil' name='idperfil'>
   <?php
        $res=ListaActivos("Perfil",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idperfil"]."'";
           echo">".$res[$x]["nombre"]."</option>";
        }
   ?>	  
   </select>
	</p>
   <br />
   <p>
	<label>Impresora<br /> </label>
   <select id='idimpresora' name='idimpresora'>
   <?php
        $res=ListaActivos("Impresora",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idimpresora"]."'";
           echo">".$res[$x]["nombre"]."</option>";
        }
   ?>	  
   </select>
	</p>
   <br />
   <p>
	<label>Ruta<br /> </label>
   <select id='idruta' name='idruta'>
   <?php
        $res=ListaActivos("Ruta",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idruta"]."'";
           echo">".$res[$x]["nombre"]."</option>";
        }
   ?>	  
   </select>
	</p>

   <br />
   <p>
	<label>Estaci&oacute;n<br /> </label>
   <select id='idestacion' name='idestacion'>
   <?php
        $res=ListaActivos("Estacion",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idestacion"]."'";
           echo">".$res[$x]["nombre"]."</option>";
        }
   ?>	  
   </select>
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
