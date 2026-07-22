<?php
include_once('controles.php');
inicio("liquiviaje");
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
$mensaje="";
echo "----..".$lestacion;
if(isset($_GET["do"]) && $_GET["do"]=="cerrar" ){
  $elresm = CierraViaje($lviaje,$lestacion);
  if($elresm=="")
    $mensaje="El viaje se cerr&oacute; correctamente";
  else
    $mensaje=$elresm;
}

?>
<html>
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
 <title>Sistemas de ventas de tiquetes</title> 
 <link rel="stylesheet" href="css/general.css" type="text/css" />
 <link rel="stylesheet" href="style.css" type="text/css" />
 <link rel="stylesheet" href="css/liquiviaje.css" type="text/css" />
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

function cerrarviaje(){
  if(confirm("Esta seguro(a) que desea cerrar este viaje?")){
    document.forms[0].action="liquiviaje.php?do=cerrar";
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
      <form name='formlasiento' method='post' action='liquiviaje.php'>
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
              if($res[$x]["liquidado"]==1)
                $liq = " Liquidado";
              else
                $liq = " Preliminar";
              echo "<option value='". $res[$x]["idviaje"]."'";
              if($res[$x]["idviaje"]==$lviaje)
                echo " selected ";

              echo">".substr($res[$x]["fecha"],0,10)." ".$res[$x]["hora"]." ".$res[$x]["tipoviaje"].$liq."</option>";
            }
            ?>
          </select>
          <br/><input type='submit' value='Volver a cargar los datos' >
          <br/> <input type='button' value='imprimir' onclick='window.print();'>
          <?php 
          if (ObjetoValido("botonliq")){
           ?>
           <br/><input type='button' value='Cerrar este viaje' onclick='cerrarviaje();'>
           <?php
         }
         ?>
         <div style='clear:both;'><h2 style='color:blue;'> <?php echo $mensaje; ?> </h2></div>
       </div>
       <div id='divdatos'>
        <div id='divtitulo'>
         <div id='infoviaje'>
          <?php

          $res = DatosViaje($lviaje,$lestacion);

          if(count($res)>0){
           echo "Fecha liquidaci&oacute;n ".$res["fechaliq"]. " <strong style='font-size:15pt;margin-left:10px;'>Liquidaci&oacute;n del viaje ".$lviaje."</strong><br/>";
           echo "Bus: ".$res["bus"]." Socio: ".$res["socio"]. " ";
           echo "Chofer: ".$res["chofer"]."<br/>";
           echo "Fecha de salida: ".fechatoNatural(substr($res["fecha"],0,10) ). " ".$res["hora"] ." ";
           echo "Tipo de viaje: ".$res["destipoviaje"];
           if($res["extra"]==1)
            echo " EXTRA  ";
          else
            echo "  ";
          if($res["liquidado"]==1)
            echo "<strong> VIAJE LIQUIDADO EL ".$res["fechaliq"]."</strong>";
          else
            echo "<strong>VIAJE EN PRELIMINAR</strong>";
        }
        ?>          
      </div>
      <p>Liquidaci&oacute;n de un Viaje</p>
    </div>
    <div id='divcuadro'>
     <?php 
     $dat = LiquidaViaje($lviaje);
     $maxpag=30;
     $lineapag=1;
     $cont=1;
     $montotot = 0;
     $can=0;
     $enca= "<div class='divpag'><table id='tablacontenido'>

     <thead>
      <tr>
        <th class='linea_abajo'>Ruta</th>
        <th class='linea_abajo'>Destino</th>
        <th class='linea_abajo'>Tipo</th>
        <th class='linea_abajo'>Terminal</th>
        <th class='linea_abajo'>Web</th>        
        <th class='linea_abajo'>Cantidad</th>
        <th class='linea_abajo'>Precio</th>
        <th class='linea_abajo'>Total</th>
      </tr>
    </thead>
    <tbody>";

      $par ='xxxx';
      for($x=0;$x<count($dat);$x++){

        $enca .= "<tr>";
        $enca .= "<td>" . $dat[$x]["ruta"] . "</td>";
        $enca .= "<td>" . $dat[$x]["parada"] . "</td>";
        $enca .= "<td>" . $dat[$x]["producto"] . "</td>";
        $enca .= "<td class='derecha'>" . $dat[$x]["terminal"] . "</td>";
        $enca .= "<td class='derecha'>" . $dat[$x]["web"] . "</td>";
        $enca .= "<td class='derecha'>" . $dat[$x]["cantidad"] . "</td>";
        $enca .= "<td class='derecha'>" . number_format($dat[$x]["precio"],0) . "</td>";
        $enca .= "<td class='derecha'>" . number_format($dat[$x]["vendido"],0) . "</td>";
        $enca .= "</tr>";
        $montotot += $dat[$x]["vendido"];
        $can+= $dat[$x]["cantidad"];
        $cont++;
        $lineapag++;
      }
      $enca .= "</tbody>";
      $enca .= "</table>";



      echo "<div style='
      float: right;
      margin-right: 30px;
      border: solid thin;
      margin-top: 10px;
      padding: 7px;'><table id='tablacontenido' >";
      echo "<tr>";
      echo "<td class='derecha masgrande' colspan='3' >Totales</td>";
      echo "<td class='derecha masgrande'>" . number_format($can,0) . "</td>";
      echo "<td class=''></td>";
      echo "<td style='min-width:85px;' class='derecha masgrande'>" . number_format($montotot,0) . "</td>";
      echo"</tr>";
      $res=ListaActivos("Ruta",array("idruta = '" . $lruta . "'"));
      $xmontosocio = 0;
      for($x=0;$x<count($res);$x++){
        $xmontosocio = $res[$x]["montosocio"];
      }
      echo "<tr>";
      echo "<td  class='derecha masgrande' colspan='5' >Monto por Salida</td>";
      echo "<td class='derecha masgrande'>" . number_format($xmontosocio,0) . "</td>";
      echo"</tr>";
      echo "<tr>";
      echo "<td  class='derecha masgrande' colspan='5'>Total</td>";
      echo "<td class='derecha masgrande'>" . number_format($montotot-$xmontosocio,0) . "</td>";
      echo"</tr>";
      echo "</table></div>";
      echo $enca;
      echo "</div>";

      ?>

   <?php 
   /*
       $dat = AsientosxWeb($lviaje);
       $menor=$dat[0]["asiento"];
       for($y=0;$y<count($dat);$y++){
        for($x=$y+1;$x<count($dat);$x++){
           if($dat[$x]["asiento"]<$dat[$y]["asiento"]){
               $temp=$dat[$y];
               $dat[$y]=$dat[$x];
               $dat[$x]=$temp;
           }
        }
       }

       $maxpag=30;
       $lineapag=1;
       $cont=1;
       echo "<br><div class='tiquetes'>";
       $par ='xxxx';
       if (count($dat)>0){
        echo "Tiq Web: ";
       }
       for($x=0;$x<count($dat);$x++){
            if($dat[$x]["asiento"]>0)
             $as=$dat[$x]["asiento"];
            else
              $as="De pi&eacute;";
              
            //echo "<tr><td>";
            echo $as;
            echo " ";
            //echo "</td><td>";
            echo $dat[$x]["factura"];
            echo " ";
            //echo "</td><td>";
            echo " ";
            echo substr($dat[$x]["nombre"],0,20);
            //echo "</td><td> [ ";
            echo " ";
            echo $dat[$x]["serial"];
            echo ",&nbsp&nbsp&nbsp";
            //echo " ] "; 
            //echo"</td></tr>";
           $cont++;
           $lineapag++;
       }
       echo "</div>";
     */  
   ?>




      <div id="firmas" style='margin-bottom:20px;margin-top:30px;clear:both;'>
       <table width='100%'>
        <thead>
          <tr>
           <th></th> <th style='border-top:solid 1px;padding:5px;'>Cajera</th><th></th><th style='border-top:solid 1px;padding:5px;'>Chofer</th><th></th>
         </tr>
       </thead>
     </table>
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

