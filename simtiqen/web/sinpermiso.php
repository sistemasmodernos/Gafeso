<?php
 include_once('controles.php');
  inicio("sinpermiso");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
 if(isset($_POST["cbperfil"]))
  $lperfil=$_POST["cbperfil"];
else
  $lperfil=1;
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />      
    		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
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
    Menu();
  
?>

<div id='cuerpo' class='content'>
<div>
  <h1> Lo sentimos pero no tiene derechos suficientes para entrar en esta opci&oacute;n </h1> 
  </div>
</div>
<?php
  Pie();
   
?>


</body>
</html>

