<?php
  include_once('controles.php');
  inicio("proconsuhora");


  if(isset($_POST["cbruta"]))
    $lruta=$_POST["cbruta"];
  else
    $lruta=$_SESSION["parametros"]["rutadefault"];
  
  if(isset($_POST["cbestacion"]))
    $lestacion=$_POST["cbestacion"];
 else
    $lestacion=$_SESSION["parametros"]["estaciondefault"];
    
$lbus="";
$lchofer="";
$lcobrador="";
$lviaje="";
$lextra=0;
$lhora="";
$lfecha="";
$inicio=date("Y-m-d",time());
  if(isset($_GET["id"]))   //manda el get
    $_POST["txtidviaje"]=$_GET["id"];
  
  
  
  if(isset($_POST["txtidviaje"]) && $_POST["txtidviaje"]!='0'){
      //significa que hay que cargar los datos del viaje
      $elfiltro = array();
      $lviaje=$_POST["txtidviaje"];
	   array_push($elfiltro,"v.idviaje = ".$lviaje); 
      $consulta = ListaMantenimiento("Viaje",$elfiltro,null);
      if (count($consulta)> 0){
          $lfecha =fechatoNormal($consulta[0]["fecha"]);
          $lruta = $consulta[0]["idruta"];
          $lestacion=$consulta[0]["estadi"];
          $lhora = $consulta[0]["hora"];
          $lbus= $consulta[0]["idbus"];
          $lcobrador=$consulta[0]["idcobrador"];
          $lchofer=$consulta[0]["idchofer"];
          $lextra = $consulta[0]["extra"];
          
      }
  }
  else
  { $dd= DatosDefault($lruta,$lestacion);
  $lviaje=$dd["idviaje"];
      }
  
  $fin = date('Y-m-d',strtotime($inicio." + 330 days"));
  $viajes=Viajes($lruta,$lestacion,$inicio,$fin);




?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <link type="text/css" href="css/tiquetes.css" rel="stylesheet" />       
        <link type="text/css" href="css/buses.css" rel="stylesheet" />                
        <script type="text/javascript" src="js/sfunciones.js"></script>
        <script type="text/javascript" src="js/vistahora.js"></script>        
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.print.css' media='print' />
<script type='text/javascript' src='fullcalendar/fullcalendar.min.js'></script>
 <style type='text/css'>
	#calendar {
		width: 400px;
		margin: 0 auto;
        font-size:120%;
		}
</style>
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
            $(function(){
                traecalendario();
                setTimeout('traecalendario()', 5000);
                setTimeout('actualiza_consecutivo()',3000);
	    var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();

		$('#calendar').fullCalendar({
			editable: true,
			events: [
          <?php

            for($x=0;$x<count($viajes);$x++){
                $fe = strtotime(substr($viajes[$x]["fecha"],0,10)." ".$viajes[$x]["hora"]);
               echo "{
                    id: ".$viajes[$x]["idviaje"].",
					title: '".$viajes[$x]["hora"]." ".$viajes[$x]["placa"]." ".$viajes[$x]["vendidos"]."/".$viajes[$x]["cantpas"]."',
					start: new Date(".date("Y",$fe).", ".date("m",$fe)."-1, ".date("d",$fe).",".date("G",$fe).",".date("i",$fe)."),
                    backgroundColor:'".$viajes[$x]["color"]."',
					url: 'vistahora.php?id=".$viajes[$x]["idviaje"]."'
				},
                ";
            }
            ?>
			]
		});
        ajusta();
        cambiaviaje();
        
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
<form name='formvista' method='post' action='vistahora.php'>
<div>
Estaci&oacute;n<select id='cbestacion' onchange='document.forms[0].submit();'>
        <?php
        $res=ListaActivos("Estacion",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idestacion"]."'";
            if($res[$x]["idestacion"]==$lestacion)
                echo " selected ";
            echo">".$res[$x]["nombre"]."</option>";
        }
    ?>
  </select>
  <br/>
        Ruta:<select id='cbruta' name='cbruta' onchange='cambiaruta();'>
        <?php
        $res=ListaActivos("Ruta",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idruta"]."'";
            if($res[$x]["idruta"]==$lruta)
                echo " selected ";
            echo">".$res[$x]["nombre"]."</option>";
            }
        ?>
        </select>
        <br/>
</div>
<div style='float:left;'>
    <div id='calendar'></div>
</div>
<div id='formabus'>
</div>
<div id='calendario'>
</div>        
<input type='hidden' id='calenhora'>
<input type='hidden' id='txtidviaje' name='txtidviaje' value='<?php echo $lviaje ?>' >

</form>
</div>
</div>
<?php
  Pie();
?>



</body>
</html>
