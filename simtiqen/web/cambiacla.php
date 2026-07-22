<?php
  include_once('controles.php');
  inicio("cambiocla");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  $lvisible='none';
  $lmensaje='';
  if(isset($_POST["txtclaveant"])){
      $lmensaje=CambiaClave($_POST["txtclaveant"],$_POST["txtclavenue"]);
      $lvisible='inline';
  }
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/cambiacla.css" type="text/css" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
  		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
        	$(document).ready(function() {
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
  <form name='formcambio' action='cambiacla.php' method='post'>
      <p><h1>Cambio de Contrase&ntilde;a</h1></p>
      Nombre del usuario: <?php echo $_SESSION["parametros"]["nombrelargo"]; ?><br/>
      Por favor digite la contrase&ntilde;a anterior: <input type='password' id='txtclaveant' name='txtclaveant'><br/>
      Por favor digite la nueva contrase&ntilde;a: <input type='password' id='txtclavenue' name='txtclavenue'><br/>
        <input type='submit' value='Cambiar contrase&ntilde;a'>
      <div style='margin-top:20px;'>
        <div id='mensaje' style='display:<?php echo $lvisible;  ?>;' >
            <?php echo $lmensaje;?>
        </div>
        </div>
        
   </form>     
  </div>
   </div>
<?php
  Pie();
   
?>


</body>
</html>