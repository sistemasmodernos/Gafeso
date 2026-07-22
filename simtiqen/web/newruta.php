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

<?php
if(isset($_POST['submit'])){
	$nomruta = htmlspecialchars(trim($_POST['nomruta']));
	if (isset($_POST['activo'])){
    	$activo = 1;
	}else{
		$activo = 0;
	}
	$estasale = htmlspecialchars(trim($_POST['estasale']));
	$estallega = htmlspecialchars(trim($_POST['estallega']));
	$abreviatura = htmlspecialchars(trim($_POST['abreviatura']));
	$montosocio = htmlspecialchars(trim($_POST['montosocio']));
  $idempresa = htmlspecialchars(trim($_POST['idempresa']));
?>
	<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<br />
	<p align="center">
<?php	
    if (Mantenimiento("Ruta",array("nombre"=>$nomruta,"activo"=>$activo,"estasale"=>$estasale,"estallega"=>$estallega,"abreviatura"=>$abreviatura,"montosocio"=>$montosocio,"idempresa"=>$idempresa),1) == true){
		echo 'Datos guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	}
?>
	<a href="manruta.php">Regresar al mantenimiento</a>
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
    <form id="frmNuevo" name="frmNuevo" method="post" action="newruta.php" enctype="multipart/form-data" onSubmit="GrabarDatos(); return false">
    <p>
    <label>Nombre de la ruta<br />
    <input class="text" type="text" name="nomruta" id="nomruta" />
    </label>
	</p>
 	<br>
     <p>
	  <input type="checkbox" name="activo" id="activo" value="1" checked> Activo<br>
	  </p>
      <br />
        <p>
	  <label>Estaci&oacute;n de Salida<br /> </label>
	  <select id='estasale' name='estasale'>
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
        <p>
	  <label>Estaci&oacute;n de Llegada<br /> </label>
	  <select id='estallega' name='estallega'>
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
