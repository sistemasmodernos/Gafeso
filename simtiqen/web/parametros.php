<?php
  include_once('controles.php');
  inicio("manparametro");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  $lmensaje="";
 if(isset($_POST["txttres"] )){
     CambiaParametro('TRES',1,$_POST["txttres"]);
     CambiaParametro('HCOR',1,$_POST["txthcor"]);
     $lmensaje="Los datos fueron actualizados";
}
$ltres= DevParametro('TRES',1);
$lhcor=DevParametro('HCOR',1);
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
        <script type="text/javascript" src="js/reimtiq.js"></script>
  		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
          <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>                
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
        	$(document).ready(function() {
  $('#txttres').numeric({minValue:0,increment:1,emptyValue: 0});
  $('#txthcor').numeric({minValue:0,increment:1,emptyValue: 0});
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
<form name='formpara' action='parametros.php' method='post'>
<div>
<p>
 Minutos antes que se puede utilizar el horario: <input type='text' name='txttres' id='txttres' value ='<?php  echo $ltres ?>'> <br/>
 Minutos que permanece vivo un apartado <input type='text' name='txthcor' id='txthcor' value='<?php echo $lhcor?>'<br/>
 </p>
 <input type='submit' value='Guardar Cambios'><br/>
 <span class='mensaje'><?php echo $lmensaje; ?></span>
 </div>
 </form>
</div>
</div>
<?php
  Pie();
   
?>


</body>
</html>

