<?php
include_once('controles.php');
inicio("repconsetiq");
include_once('lib/nucleo.php');
include_once('lib/utiles.php');
if(isset($_POST["txtfechai" ]))
  $lfechai=$_POST["txtfechai" ];
else
  $lfechai=date("d/m/Y",time());

if(isset($_POST["txtfechaf" ]))
  $lfechaf=$_POST["txtfechaf" ];
else
  $lfechaf=date("d/m/Y",time());


?>
<html>
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
 <title>Sistemas de ventas de tiquetes</title> 
 <link rel="stylesheet" href="css/general.css" type="text/css" />
 <link rel="stylesheet" href="style.css" type="text/css" />
 <link rel="stylesheet" href="css/lconsetiq.css" type="text/css" />
 <link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
 <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
 <script type="text/javascript" src="js/sfunciones.js"></script>
 <script type="text/javascript" src="js/reimtiq.js"></script>
 <script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
 <script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
 <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
 <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
 <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>  
 <script type="text/javascript" src="js/jquery.timeentry-es.js"></script>
 <script type="text/javascript" src="js/jquery.timeentry.min.js"></script>
 <script type="text/javascript" src="js/jquery.timeentry.js"></script>        
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

      <form name='formlasiento' method='post' action='repviajes.php'>
        <div id='controles'>
         Horarios desde: 
         <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'> 
         Hasta: 
         <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'>

         <br/><input type='submit' value='Volver a cargar los datos' >
         <br/> <input type='button' value='imprimir' onclick='window.print();'>
         <br/> <a href='repviajesxls.php?fechai=<?php echo $lfechai."&".$lfechaf; ?>' >Generar excel</a>
       </div>
       <div id='divdatos'>
        <div id='divtitulo'>
          <p> Ventas por Carrera 
          </p>
        </div>
        <div id='divcuadro'>
          <?php
          $dat= ListaCarreras(fechatoUTC($lfechai),fechatoUTC($lfechaf)) ;
        $maxpag=42; //cantidad de líneas que caben en una página
        $veces=1;
        $cont=1;
                echo "<div class='divpag'><table class='tabasientos'>".
                "<thead>
                <tr>
                  <th>D&iacute;a</th>
                  <th>Mes</th>
                  <th>A&ntilde;o</th>
                  <th colspan='2'>Unidad</th>
                  <th style='width:60px;' >Cantidad Total Pasajeros</th>
                  <th style='width:60px;' >Cantidad Pasajeros Adulto Mayor</th>
                  <th style='width:60px;' >Pasajero Equivalente</th>
                  <th>Carreras</th>
                  <th>Ingresos</th>
                </tr>
              </thead>";
      $par ='xxxx';
      $total=0;
      for($x=0;$x<count($dat);$x++){
       if($cont>=$maxpag)
       {
         $cont=1;
         //if($veces>1)
                  $maxpag=48; //cantidad de líneas que caben en una página

            $veces++;
            echo "</table></div><div class='divpag'><table class='tabasientos'>".
                "<thead>
                <tr>
                  <th>D&iacute;a</th>
                  <th>Mes</th>
                  <th>A&ntilde;o</th>
                  <th colspan='2'>Unidad</th>
                  <th style='width:60px;'>Cantidad Total Pasajeros</th>
                  <th style='width:60px;'>Cantidad Pasajeros Adulto Mayor</th>
                  <th style='width:60px;'>Pasajero Equivalente</th>
                  <th>Carreras</th>
                  <th>Ingresos</th>
                </tr>
              </thead>";
            }
          $dividido = explode(" ", $dat[$x]["placa"]);
          echo "<tr>
                <td>".substr($dat[$x]["fecha"],8,2)."</td>
                <td>".substr($dat[$x]["fecha"],5,2)."</td>
                <td>".substr($dat[$x]["fecha"],0,4)."</td>
                <td style='padding-left:10px;'>" . $dividido[0] . "</td>
                <td style='padding-left:10px;'>" . $dividido[1] . "</td>
                <td class='cmonto' >".($dat[$x]["equivalente"]+$dat[$x]["adulto"])."</td>
                <td class='cmonto' >".$dat[$x]["adulto"]."</td>
                <td class='cmonto' >".$dat[$x]["equivalente"]."</td>
                <td class='cmonto' >".$dat[$x]["carreras"]."</td>
                <td class='cmonto' >".number_format($dat[$x]["monto"])."</td>
                </tr>";
         $cont++;
       }
       echo "</table></div>";
       ?>
     </div>
   </div>
 </div>
</form>
</div>
<?php
Pie();

?>


</body>
</html>

