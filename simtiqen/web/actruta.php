<?php
  include_once('controles.php');
  inicio("manruta");
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="css/mantenimiento.css" type="text/css" />
  <script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
  <script src="js/manruta.js" type="text/javascript"></script>
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
	$idruta = htmlspecialchars(trim($_POST['idruta']));
	$nomruta = htmlspecialchars(trim($_POST['nomruta']));
	if (isset($_POST['activo'])){
    	$activo = 1;
	}else{
		$activo = 0;
	}
	$estasale = htmlspecialchars(trim($_POST['estasale']));
	$estallega = htmlspecialchars(trim($_POST['estallega']));
  $idempresa = htmlspecialchars(trim($_POST['idempresa']));
	$abreviatura = htmlspecialchars(trim($_POST['abreviatura']));
	$montosocio = htmlspecialchars(trim($_POST['montosocio']));
	?>
</p>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<br />
	<p align="center">
<?php	
	if (Mantenimiento("ruta",array("idruta"=>$idruta,"nombre"=>$nomruta,"activo"=>$activo,"estasale"=>$estasale,"estallega"=>$estallega,"abreviatura"=>$abreviatura,"montosocio"=>$montosocio,"idempresa"=>$idempresa),2) == true){
		echo 'Datos Guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	} 
?>
    <br />
    <br />
	<a href="manruta.php">Regresar al mantenimiento</a>
	</p>
    </div>
</div>
<?php
}else{
	if(isset($_GET['idruta'])){
		$idruta = $_GET['idruta'];
   	$elfiltro = array();
	   array_push($elfiltro,"idruta = ".$idruta); 
      $consulta = ListaMantenimiento("ruta",$elfiltro,null);
      if (count($consulta)> 0){
      	$nombre = $consulta[0]["nombre"];
      	$activo = $consulta[0]["activo"];
      	$estasale = $consulta[0]["estasale"];
      	$estallega = $consulta[0]["estallega"];
      	$abreviatura = $consulta[0]["abreviatura"];
      	$montosocio = $consulta[0]["montosocio"];
        $idempresa = $consulta[0]["idempresa"];
	?>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<form id="frmActualizar" name="frmActualizar" method="post" action="actruta.php" enctype="multipart/form-data" onSubmit="ActualizarDatos(); return false">
    	<input type="hidden" name="idruta" id="idruta" value="<?php echo $idruta ?>" />
        <p>
	  <label>Nombre de la Estaci&oacute;n<br />
	  <input class="text" type="text" name="nomruta" id="nomruta" value="<?php echo $nombre ?>" />
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
	  <label>Estaci&oacute;n de Salida<br /> </label>

	  <select id='estasale' name='estasale'>
     <?php
        $res=ListaActivos("Estacion",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idestacion"]."'";
           if ($res[$x]["idestacion"] == $estasale){
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
	  <label>Estaci&oacute;n de Llegada<br /> </label>

	  <select id='estallega' name='estallega'>
     <?php
        $res=ListaActivos("Estacion",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idestacion"]."'";
           if ($res[$x]["idestacion"] == $estallega){
           	  echo " selected ";
           	}
           echo">".$res[$x]["nombre"]."</option>";
        }
     ?>	  
     </select>
	  </p>
     <br />
        <p>
	  <label>Abreviatura de la Estaci&oacute;n para los tiquetes<br />
	  <input class="text" type="text" name="abreviatura" id="abreviatura" value="<?php echo $abreviatura ?>" />
	  </label>
	  </p>
      <br />

        <p>
	  <label>Monto por Salida de Autobus para Liquidaciones<br />
	  <input class="text" type="number" name="montosocio" id="montosocio" value="<?php echo $montosocio ?>" />
	  </label>
	  </p>
      <br />
        <p>
    <label>Empresa Asociada<br /> </label>

    <select id='idempresa' name='idempresa'>
     <?php
        $res=ListaActivos("Empresa",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idempresa"]."'";
           if ($res[$x]["idempresa"] == $idempresa){
              echo " selected ";
            }
           echo">".$res[$x]["nombre"]."</option>";
        }
     ?>   
     </select>
    </p>
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
         	echo "<a href='manruta.php'>Regresar al mantenimiento</a>";
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
