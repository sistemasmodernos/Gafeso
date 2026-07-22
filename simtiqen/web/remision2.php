<?php
include_once('controles.php');
include_once('lib/nucleo.php');
include_once('lib/utiles.php');
inicio("repenco");

if(isset($_POST["cbestacion"]))
  $lestacion=$_POST["cbestacion"];
else
  $lestacion=$_SESSION["parametros"]["estaciondefault"];

if(isset($_POST["cbparadades"]))
  $lparadades=$_POST["cbparadades"];
else
  $lparadades=$_SESSION["parametros"]["paradaenco"];


if(isset($_POST["cbruta"]))
  $lruta=$_POST["cbruta"];
else
  $lruta=$_SESSION["parametros"]["rutadefault"];


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
 $dd= DatosDefault($_SESSION["parametros"]["rutadefault"],$lestacion);
 $lviaje=$dd["idviaje"];
}

if(isset($_POST["ckrollo"]) )   {
  $ckrollo=$_POST["ckrollo"];
}
else{
 $ckrollo="1";
}

$mensaje = "";
if(isset($_GET["do"]) && $_GET["do"]=="cerrar" ){
  $elresm = CierraEncomienda($lviaje);
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
 <link rel="stylesheet" href="css/remision.css" type="text/css" />
 <link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
 <script type="text/javascript" src="js/sfunciones.js"></script>
 <script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
 <script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
 <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>        
 <script type="text/javascript">
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

   function cerrarviajeenco(){
    if(confirm("Esta seguro(a) que desea cerrar este viaje?")){
     document.forms[0].action="remision2.php?do=cerrar";
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
      <form id='formremesa' method='post' action='remision2.php'>

        <div id='controles'>
          Ruta: 
          <select id='cbruta' name='cbruta' >
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
          Estaci&oacute;n emisi&oacute;n <select id='cbestacion' name='cbestacion' onchange='cambiaestacion(this.value);'>
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
        Parada destino  <select id='cbparadades' name='cbparadades' >
        <option value=''>Todas</option>
        <?php
        $res=ListaActivos("Parada",null);
        for($x=0;$x<count($res);$x++){
          echo "<option value='". $res[$x]["idparada"]."'";
   /*       if($res[$x]["idparada"]==$lparadades)
            echo "selected";
            */
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

          echo">".substr($res[$x]["fecha"],0,10)." ".$res[$x]["hora"] . " " . $res[$x]["tipoviaje"];
          if ($res[$x]["liqenco"] == "1")
          {
            echo " Liquidado";
          }else{
            echo "";
          }
          echo "</option>";
        }
        ?>
      </select>
      <br/>



      <span >Formato de impresi&oacute;n: </span>
      <input type="radio" name="ckrollo" id="ckrollo" value="1" <?php if ($ckrollo=="1") echo "checked"; ?> >Rollo
      <input type="radio" name="ckrollo" id="ckrollo" value="2" <?php if ($ckrollo=="2") echo "checked"; ?> >Normal


      <br/><input type='submit' value='Volver a cargar los datos' >
      <br/> <input type='button' value='imprimir' onclick='window.print();'>
      <?php 
      if (ObjetoValido("botliqenco")){
        ?>
        <br/><input type='button' value='Cerrar este viaje' onclick='cerrarviajeenco();'>
        <?php
      }
      ?>
      <div style='clear:both;'><h2 style='color:blue;'> <?php echo $mensaje; ?> </h2></div>
    </div>
    <div id='divdatos'>
      <div id='divtitulo'>
       <div id='infoviaje'>
        <?php

        $res = Remision($lviaje,$lestacion,$lparadades);
        if(count($res)>0){
          echo "Bus: ".$res[0]["placa"]."<br/>";
          echo "Chofer: ".$res[0]["chofer"]."<br/>";
          echo "Cobrador: ".$res[0]["cobrador"]."<br/>";
          echo "Fecha de salida ".fechatoNatural(substr($res[0]["fecha"],0,10) ). " ".$res[0]["hora"] ;
          if ($res[0]["liqenco"] == "1"){
           echo " Liquidado";
         }else
         {
           echo " Preliminar";
         }
       }
       ?>          
     </div>
     <p> Reporte de encomiendas </p>
   </div>
   <div style='margin-top:40px;clear:both;'>
     <table class='tablaenco'>
      <thead>
        <tr>
          <?php if ($ckrollo=="1"){ ?>
            <th style="border-bottom:solid thin;">N. Gu&iacute;a<br/>
              Descripci&oacute;n<br/>
              Persona que env&iacute;a<br/>
              Persona que retira<br/>
              Bultos, Tipo de encomienda y Monto</th>
              <?php }else{ ?>
                <th style="border-bottom:solid thin;">N. Gu&iacute;a</th>
                <th style="border-bottom:solid thin;">Descripci&oacute;n</th>
                <th style="border-bottom:solid thin;">Bultos</th>
                <th style="border-bottom:solid thin;">Persona que env&iacute;a</th>
                <th style="border-bottom:solid thin;">Persona que retira</th>
                <th style="border-bottom:solid thin;">Tipo de encomienda</th>
                <th style="border-bottom:solid thin;">Monto</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>

              <?php
              $total=0;
              $cantidad=0;
               $grupoparada="";   
              if(count($res)>0)
               for($x=0;$x<count($res);$x++)
               {

                if($res[$x]["declarado"]!=0)
                  $eltipo="Valor Declarado";
                else
                  $eltipo="Normal";
                if ($ckrollo=="1"){

                  if($grupoparada!=$res[$x]["parada"]){
                    $grupoparada=$res[$x]["parada"];
                    echo "<tr><th></th></tr>";
                    echo "<tr><th>----- ".$grupoparada." -----</th></tr>";
                  }
                  echo " <tr> 
                  <td style='border-bottom:solid thin;'>".$res[$x]["factura"]. "<br/>
                   ".$res[$x]["nota"]."<br/>
                   ".$res[$x]["remitente"]."<br/>
                   ".$res[$x]["destinatario"]."<br/>
                   Bultos: ".$res[$x]["cantbul"].", Tipo
                   ".$eltipo.", 
                   ".number_format($res[$x]["monto"])."</td>
                 </tr>
                 ";
               }else{
                echo " <tr> 
                <td>".$res[$x]["factura"]. "</td>
                <td>".$res[$x]["nota"]."</td>
                <td style='text-align:center;'>".$res[$x]["cantbul"]."</td>
                <td>".$res[$x]["remitente"]."</td>
                <td>".$res[$x]["destinatario"]."</td>
                <td>".$eltipo."</td>
                <td style='text-align:right;'>".number_format($res[$x]["monto"])."</td>
              </tr>
              ";
            }
            $total=$total+$res[$x]["monto"];
            $cantidad++;
          }
          else
            echo "No existen encomiendas para este horario";
          ?>
        </tbody>
        <tfoot>
          <tr>
            <?php if ($ckrollo=="1"){ ?>
              <th style="border-top:solid thin;">Cantidad de gu&iacute;as <?php echo $cantidad; ?>, Total <?php echo number_format($total); ?> </th>
              <?php }else{ ?>
                <th colspan='6' style="border-top:solid thin;">Cantidad de gu&iacute;as <?php echo $cantidad; ?></th>
                <th style="border-top:solid thin;">Total <?php echo number_format($total); ?> </th>
                <?php } ?>
              </tr>
            </tfoot>
          </table>
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
