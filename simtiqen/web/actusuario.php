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

<!--Inicio /!-->
  <?php
if(isset($_POST['submit'])){
	$idUsuario = htmlspecialchars(trim($_POST['idUsuario']));
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
</p>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<br />
	<p align="center">
<?php	
	if (Mantenimiento("usuario",array("idUsuario"=>$idUsuario,"nombre"=>$nombre,"nombrelargo"=>$nombrelargo,"email"=>$email,"activo"=>$activo,"idimpresora"=>$idimpresora,"idruta"=>$idruta,"idestacion"=>$idestacion,"idperfil"=>$idperfil),2) == true){
		echo 'Datos Guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	} 
?>
    <br />
    <br />
	<a href="manusuario.php">Regresar al mantenimiento</a>
	</p>
    </div>
</div>
<?php
}else{
	if(isset($_GET['idUsuario'])){
		$idUsuario = $_GET['idUsuario'];
   	$elfiltro = array();
	   array_push($elfiltro,"idUsuario = ".$idUsuario); 
      $consulta = ListaMantenimiento("usuario",$elfiltro,null);
      if (count($consulta)> 0){
      	$nombre = $consulta[0]["nombre"];
      	$nombrelargo = $consulta[0]["nombrelargo"];
      	$email = $consulta[0]["email"];
      	$activo = $consulta[0]["activo"];
      	$idimpresora = $consulta[0]["idimpresora"];
      	$idperfil = $consulta[0]["idperfil"];
      	$idruta = $consulta[0]["idruta"];
      	$idestacion = $consulta[0]["idestacion"];
	?>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<form id="frmActualizar" name="frmActualizar" method="post" action="actusuario.php" enctype="multipart/form-data" onSubmit="ActualizarDatos(); return false">
    	<input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario ?>" />
     <p>
	  <label>C&oacute;digo del Usuario<br />
	  <input class="text" type="text" name="nombre" id="nombre" value="<?php echo $nombre ?>" />
	  </label>
	  </p>
     <br />
     <p>
	  <label>Nombre del Usuario<br />
	  <input class="text" type="text" name="nombrelargo" id="nombrelargo" value="<?php echo $nombrelargo ?>" />
	  </label>
	  </p>
     <br />
     <p>
	  <label>Correo Electr&oacute;nico<br />
	  <input class="text" type="text" name="email" id="email" value="<?php echo $email ?>" />
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
	  <label>Perfil<br /> </label>
	  <select id='idperfil' name='idperfil'>
     <?php
        $res=ListaActivos("Perfil",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idperfil"]."'";
           if ($res[$x]["idperfil"] == $idperfil){
           	  echo " selected ";
           	}
           echo">".$res[$x]["nombre"]."</option>";
        }
     ?>	  
     </select>
	  </p>
     <p>
     <br />
     <p>
	  <label>Impresora<br /> </label>
	  <select id='idimpresora' name='idimpresora'>
     <?php
        $res=ListaActivos("Impresora",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idimpresora"]."'";
           if ($res[$x]["idimpresora"] == $idimpresora){
           	  echo " selected ";
           	}
           echo">".$res[$x]["nombre"]."</option>";
        }
     ?>	  
     </select>
	  </p>
     <p>
     <br />
     <p>
	  <label>Ruta<br /> </label>
	  <select id='idruta' name='idruta'>
     <?php
        $res=ListaActivos("Ruta",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idruta"]."'";
           if ($res[$x]["idruta"] == $idruta){
           	  echo " selected ";
           	}
           echo">".$res[$x]["nombre"]."</option>";
        }
     ?>	  
     </select>
	  </p>
     <p>
     <br />
     <p>
	  <label>Estaci&oacute;n<br /> </label>
	  <select id='idestacion' name='idestacion'>
     <?php
        $res=ListaActivos("Estacion",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idestacion"]."'";
           if ($res[$x]["idestacion"] == $idestacion){
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
