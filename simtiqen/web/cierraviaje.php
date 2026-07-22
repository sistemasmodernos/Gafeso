<?php
  include_once('controles.php');
  inicio("cierraviaje");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  $mensaje="";
  if(isset($_POST["cbimpresora"]))
    $limpresora=$_POST["cbimpresora"];
  else
    $limpresora=$_SESSION["parametros"]["impresoradefault"];

  if(isset($_POST["cbruta"])){
    $lruta=$_POST["cbruta"];
  }
 else{
    $lruta=$_SESSION["parametros"]["rutadefault"];
 }
  $lestacion=$_SESSION["parametros"]["estaciondefault"];    
  
 if(isset($_POST["txtfechai" ]))
    $lfechai=$_POST["txtfechai" ];
 else
    $lfechai=date("d/m/Y",time());

if(isset($_POST["txtfechaf" ]))
    $lfechaf=$_POST["txtfechaf" ];
 else
    $lfechaf=date("d/m/Y",time());
    
if(isset($_POST["cbviaje"]) )   
  $lviaje=$_POST["cbviaje"];
else{
   $dd= DatosDefault($lruta,$lestacion);
  $lviaje=$dd["idviaje"];
  }

if(isset($_GET["do"]) && $_GET["do"]=="cerrar" ){
  if(CierraViaje($lviaje,$lestacion))
    $mensaje="El viaje se cerr&oacute; correctamente";
  else
    $mensaje="";
}


?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/cierraviaje.css" type="text/css" />
    		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
        <script type="text/javascript" src="js/reimtiq.js"></script>
  		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>                
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
        	$(document).ready(function() {

		$('#txtfechai').datepicker({
					inline: true
				});
        $("#txtfechai").datepicker($.datepicker.regional['es']);

        $('#txtfechaf').datepicker({
					inline: true
				});
        $("#txtfechaf").datepicker($.datepicker.regional['es']);
        ajusta();
	});

function cerrarviaje(){
  if(confirm("Esta seguro(a) que desea cerrar este viaje?")){
    document.forms[0].action="cierraviaje.php?do=cerrar";
    document.forms[0].submit();
  }

}
            
		</script>
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
   Encabezado();
   
?>
<div class="wrap">
<?php Menu(); ?>

<div id='cuerpo' class='content'>
<form name='formlasiento' method='post' action='cierraviaje.php'>
<div id='controles'>
 Ruta: <select id='cbruta' name='cbruta' onchange='cambiaruta();'>
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
       Horarios desde: <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'> 
       Hasta: <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'>
       <br/>
         <select id='cbviaje' name='cbviaje' >
        <?php
        $res=ListaActivos("Viaje",array("fecha between '". fechatoUTC($lfechai)."' and '". fechatoUTC($lfechaf)."'","idruta=".$lruta));
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idviaje"]."'";
            if($res[$x]["idviaje"]==$lviaje)
                echo " selected ";

            echo">".substr($res[$x]["fecha"],0,10)." ".$res[$x]["hora"]."</option>";
            }
        ?>
            </select>
         <br/><input type='submit' value='Volver a cargar los datos' >
         
</div>

<div id='divdatos'>
<div id='divtitulo'>
  
      <p></p>
    </div>
  <div id='divcuadro'>
       <div id='infoviaje'>
      <?php
     
           $res = DatosViaje($lviaje,$lestacion);
           if(count($res)>0){
           echo "Bus: ".$res["bus"]."<br/>";
           echo "Chofer: ".$res["chofer"]."<br/>";
           echo "Cobrador: ".$res["cobrador"]."<br/>";
           echo "Fruta y Color : ".$res["fruta"]." ".$res["color"]."<br/>";
           echo "Fecha de salida <span style='font-size:120%;'> ".fechatoNatural(substr($res["fecha"],0,10) ). " ".$res["hora"] ."</span>";
           }
        ?>          
       </div>
    <input type='button' value='Cerrar este viaje' onclick='cerrarviaje();'>
     <div style='clear:both;'><h2 style='color:blue;'> <?php echo $mensaje; ?> </h2></div>
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

