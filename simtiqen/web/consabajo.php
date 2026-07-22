<?php
  include_once('controles.php');
  inicio("consabajo");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  include_once('lib/mysql.php');
  include_once('lib/tablabajo.php');   
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
	<title>Sistemas de ventas de tiquetes</title> 
	<link rel="stylesheet" href="css/general.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="stylesheet" href="css/cierrevta.css" type="text/css" />
	<link rel="stylesheet" href="css/transf.css" type="text/css" />
	<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="js/sfunciones.js"></script>
  	<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
    <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>  
    <script type="text/javascript" src="js/jquery.timeentry-es.js"></script>
    <script type="text/javascript" src="js/jquery.timeentry.min.js"></script>
    <script type="text/javascript" src="js/jquery.timeentry.js"></script>        
    <script type="text/javascript" src="js/constiquete.js"></script>    
     <script>
        $(document).ready(ajusta);
        </script>    
<style>
.centrado
{
	padding: 0;
	margin: 0 auto;
	border: 0;
}
.alineado
{
	text-align: center;
}
.dere
{
	text-align: right;
}

th
{
	background: #CCC;
}
</style>
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
   Encabezado();
   
?>
<div class="wrap">
<?php Menu(); ?>

<div id='cuerpo' class='content'>
    <h1 class='alineado'>Estad&iacute;sticas Generales</h1>
	<div id='itsthetable'>
		<?php
		$laTabla = new TablAbajo();
		echo $laTabla->PorcentajeVenta();
		echo $laTabla->Ocupacion();
		echo $laTabla->PorDiaDeLaSemana();
		echo $laTabla->PorEstacion();
		echo $laTabla->PorRuta();
		echo $laTabla->PorProducto();
		echo $laTabla->PorProductoRuta();
		echo $laTabla->PorRutaProducto();
		echo $laTabla->PorParada();
		?>
	</div>
</div>
<?php
  Pie();
?>


</body>
</html>

