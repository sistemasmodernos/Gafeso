<?php
  include_once('controles.php');
  inicio("replistaasie");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  if(isset($_POST["cbimpresora"]))
    $limpresora=$_POST["cbimpresora"];
  else
    $limpresora=$_SESSION["parametros"]["impresoradefault"];

  if(isset($_POST["cbruta"]))
    $lruta=$_POST["cbruta"];
 else
    $lruta=$_SESSION["parametros"]["rutadefault"];
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
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/lasientos.css" type="text/css" />
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
            
		</script>
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
   Encabezado();
   
?>
<div class="wrap">
<?php Menu(); ?>

<div id='cuerpo' class='content'>
<form name='formlasiento' method='post' action='lasientos.php'>
<div id='controles'>
    <select id='cbruta' name='cbruta' onchange='cambiaruta();'>
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
         <br/> <input type='button' value='imprimir' onclick='window.print();'>
</div>
<div id='divdatos'>
<div id='divtitulo'>
       <div id='infoviaje'>
      <?php
     
           $res = DatosViaje($lviaje,$lestacion);
           if(count($res)>0){
           echo "Bus: ".$res["bus"]."<br/>";
           echo "Chofer: ".$res["chofer"]."<br/>";
           echo "Cobrador: ".$res["cobrador"]."<br/>";
           echo "Fruta y Color : ".$res["fruta"]." ".$res["color"]."<br/>";
           echo "Fecha de salida ".fechatoNatural(substr($res["fecha"],0,10) ). " ".$res["hora"] ;
           }
        ?>          
       </div>
      <p> Listado de Asientos </p>
    </div>
<div id='divcuadro'>
   <?php 
       $dat = Asientosxnum($lviaje);
       //primero a ordenar la matriz por parada usando metodo de burbuja
       $menor=$dat[0]["parada"];
       for($y=0;$y<count($dat);$y++){
        for($x=$y+1;$x<count($dat);$x++){
           if($dat[$x]["parada"]<$dat[$y]["parada"]){
               $temp=$dat[$y];
               $dat[$y]=$dat[$x];
               $dat[$x]=$temp;
           }
        }
       }
       //ahora si a presentarlo
       $maxpag=30;
       $lineapag=1;
       $cont=1;
       echo "<div class='divpag'><table class='tabasientos'>";
       $par ='xxxx';
       for($x=0;$x<count($dat);$x++){
           if($lineapag>=$maxpag)
           {
               $cont=1;
               echo "</table></div><div class='divpag' ><table class='tabasientos'>";
               $lineapag=1;
            }
            if($par!=$dat[$x]["parada"]){
                $par=$dat[$x]["parada"];
               echo "<tr><td colspan='4' class='astitulo'>".substr($par,2,strlen($par)-2)."<br/><hr></td></tr>";
               $lineapag++;
               $lineapag++;
            }
            if($dat[$x]["asiento"]>0)
             $as=$dat[$x]["asiento"];
            else
              $as="De pi&eacute;";
              
            echo "<tr";
              if($dat[$x]["idestacion"]==4)
              echo" style='background-color:yellow;' ";
            echo "><td>".$as. "</td>";
            echo "<td>".$dat[$x]["factura"];
            if($dat[$x]["idestacion"]==4)
              echo" [WEB] ";
            echo "</td><td>".substr($dat[$x]["nombre"],0,20);
           if(strlen(trim($dat[$x]["serial"]))>0 && ($dat[$x]["idestacion"]==4))
            echo "</td><td> [ ".$dat[$x]["serial"]." ] "; 
           else
            echo "</td><td> ";  
           echo"</td></tr>";
           $cont++;
           $lineapag++;
       }
       echo "</table></div>";
       
   ?>
   
   <div class='divpag' id='tablamanual'>
     <table>
     <tr><td>Tiquetes normales: </td><td>___________</td></tr>
     <tr><td>Tiquetes Web:</td>      <td>___________</td></tr>
     <tr><td>Total Tiquetes:</td>    <td>___________</td></tr>
    </table>
    <table>
    <caption>Refuerzos a Orotina</caption>
       <tr><td>5:00pm:</td><td>_________</td><td></td><td>5:25am:</td><td>_________</td></tr>
      <tr><td>6:00pm:</td><td>_________</td><td></td><td>5:30am:</td><td>_________</td></tr>
      <tr><td>8:30pm:</td><td>_________</td><td></td><td>7:00am:</td><td>_________</td></tr>
    </table>
    <div style='font-size:120%;margin-top:20px;'>
    Efectivo: _______________
    </div>
   </div>
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

