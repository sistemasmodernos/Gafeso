<?php
  include_once('controles.php');
  inicio("manusu");
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
<link rel="stylesheet" href="style.css" type="text/css" />  
  <link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
  <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
  <link type="text/css" href="css/mantenimiento.css" rel="stylesheet" />       
  <script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
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

<?php
if(isset($_POST['submit'])){
	$placa = htmlspecialchars(trim($_POST['placa']));
	$color = htmlspecialchars(trim($_POST['color']));
	$cantpas = htmlspecialchars(trim($_POST['cantpas']));
	$idchofer = htmlspecialchars(trim($_POST['idchofer']));
  $idsocio = htmlspecialchars(trim($_POST['idsocio']));
  $asientosespe = htmlspecialchars(trim($_POST['asientosespe']));
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
    if (Mantenimiento("bus",array("placa"=>$placa,"color"=>$color,"activo"=>$activo,"cantpas"=>$cantpas,"idchofer"=>$idchofer,"idsocio"=>$idsocio,"asientosespe"=>$asientosespe),1) == true){
		echo 'Datos guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	}
?>
	<a href="manbus.php">Regresar al mantenimiento</a>
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
    <form id="frmNuevo" name="frmNuevo" method="post" action="newbus.php" enctype="multipart/form-data" onSubmit="GrabarDatos(); return false">
    <p>
    <label>Placa del Bus<br />
    <input class="text" type="text" name="placa" id="placa" />
    </label>
	</p>
 	<br>
    <p>
    <label>Color<br />
    <input class="text" type="text" name="color" id="color" />
    </label>
	</p>
 	<br>
    <p>
    <label>Capacidad del Bus<br />
    <input type="text" name="cantpas" id="cantpas" class='numerico'>
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
           echo">".$res[$x]["Nombre"]."</option>";
        }
     ?>	  
     </select>
	  </p>
     <p>
     <br />
    <label>Asientos Especiales<br />
    <input class="text" 
           type="text" 
           name="asientosespe" 
           id="asientosespe" 
           value="" />
    </label>
    </p>
      <br />
    <p>
  <br>
    <p>
    <label>Socio<br /> </label>
    <select id='idsocio' name='idsocio'>
     <?php
        $res=ListaActivos("Socio",null);
        for($x=0;$x<count($res);$x++){
           echo "<option value='". $res[$x]["idsocio"]."'";
           echo">".$res[$x]["Nombre"]."</option>";
        }
    ?>   
    </select>
    </p>
    <p>
  <br />

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
