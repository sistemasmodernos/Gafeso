<?php
  include_once('controles.php');
  inicio("manbus");
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/mantenimiento.css" type="text/css" />
  <link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
  <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
  <script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
  <script src="js/manbus.js" type="text/javascript"></script>
  <script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
  <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
  <script type="text/javascript">     
       $(function(){
            $('#cantpas').numeric({minValue:1,increment:1,emptyValue: 0});
            ajusta();
			});        
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
	$placa = htmlspecialchars(trim($_POST['placa']));
	$color = htmlspecialchars(trim($_POST['color']));
	$cantpas = htmlspecialchars(trim($_POST['cantpas']));
	$idchofer = htmlspecialchars(trim($_POST['idchofer']));
  $idsocio = htmlspecialchars(trim($_POST['idsocio']));
	$idbus = htmlspecialchars(trim($_POST['idbus']));
  $asientosespe = htmlspecialchars(trim($_POST['asientosespe']));
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
	if (Mantenimiento("bus",array("idBus"=>$idbus
    ,"placa"=>$placa
    ,"color"=>$color
    ,"activo"=>$activo
    ,"cantpas"=>$cantpas
    ,"idchofer"=>$idchofer
    ,"idsocio"=>$idsocio
    ,"asientosespe"=>$asientosespe ),2) == true){
		echo 'Datos Guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	} 
?>
    <br />
    <br />
	<a href="manbus.php">Regresar al mantenimiento</a>
	</p>
    </div>
</div>
<?php
}else{
	if(isset($_GET['idbus'])){
		$idbus = $_GET['idbus'];
   	$elfiltro = array();
	   array_push($elfiltro,"idbus = ".$idbus); 
      $consulta = ListaMantenimiento("bus",$elfiltro,null);
      if (count($consulta)> 0){
   		$placa = $consulta[0]['placa'];
			$color = $consulta[0]['color'];
			$cantpas = $consulta[0]['cantpas'];
			$idchofer = $consulta[0]['idchofer'];
      $idsocio = $consulta[0]['idsocio'];
      $idbus = $consulta[0]["idBus"];
      $activo = $consulta[0]["activo"];
      $asientosespe = $consulta[0]["asientosespe"];
	?>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<form id="frmActualizar" name="frmActualizar" method="post" action="actbus.php" enctype="multipart/form-data" onSubmit="ActualizarDatos(); return false">
    	<input type="hidden" name="idbus" id="idbus" value="<?php echo $idbus ?>" />
        <p>
	  <label>Placa del Bus<br />
	  <input class="text" type="text" name="placa" id="placa" value="<?php echo $placa ?>" />
	  </label>
	  </p>
      <br />
        <p>
	  <label>Color<br />
	  <input class="text" type="text" name="color" id="color" value="<?php echo $color ?>" />
	  </label>
	  </p>
      <br />
    <p>
    <label>Asientos Especiales<br />
    <input class="text" 
           type="text" 
           name="asientosespe" 
           id="asientosespe" 
           value="<?php echo $asientosespe ?>" />
    </label>
    </p>
      <br />
    <p>
    <label>Capacidad del Bus<br />
    <input class="numerico" type="text" name="cantpas" id="cantpas" value="<?php echo $cantpas ?>" />
    </label>
	</p>
 	<br>
     <p>
	  <label>Chofer<br /> </label>
	  <select id='idchofer' name='idchofer'>
     <?php
        $res=ListaActivos("Chofer",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idchofer"]."'";
           if ($res[$x]["idchofer"] == $idchofer){
           	  echo " selected ";
           	}

           echo">".$res[$x]["Nombre"]."</option>";
        }
     ?>	  
     </select>
	  </p>
     <p>
     <br />


  <br>
    <p>
    <label>Socio<br /> </label>
    <select id='idsocio' name='idsocio'>
    <?php
        $res=ListaActivos("Socio",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idsocio"]."'";
           if ($res[$x]["idsocio"] == $idsocio){
              echo " selected ";
            }

           echo">".$res[$x]["Nombre"]."</option>";
        }
    ?>   
    </select>
    </p>
    <p>
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
         	echo "<a href='manbus.php'>Regresar al mantenimiento</a>";
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
