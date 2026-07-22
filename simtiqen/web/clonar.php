<?php
  include_once('controles.php');
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  inicio("proclon");
  
?>


<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
	<title>Sistemas de ventas de tiquetes</title> 
	<link rel="stylesheet" href="css/general.css" type="text/css" />
	<link rel="stylesheet" href="css/index.css" type="text/css" />
	<link href="style.css" rel="stylesheet" type="text/css">  
  	<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="js/sfunciones.js"></script>
	<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>  
	<script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>   
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">
<?php
   Encabezado();
?>
<div class="wrap">
	<?php Menu(); ?>
	<div id='cuerpo' class='content'>
		<?php
			if(!isset($_POST["txtfechai"]))
				echo "Error Esta página no puede cargarse directamente" ;
			else{
				$res=ClonaViajes(fechatoUTC($_POST["txtfechai"]),fechatoUTC($_POST["txtfechaf"]));          
			if($res>0)
				echo "<h1> Los horarios fueron creados correctamente </h>";
			else
				echo "<h1> Ocurrió un error al crear los horarios </h>";
			}
		?>
	</div>
</div>
<?php
  Pie();
?>
</body>
</html>