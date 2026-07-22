<?php
  include_once('controles.php');
  inicio("repchofer");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');

    if(isset($_POST["txtfechai"]))
   $lfechai=$_POST["txtfechai"];
  else
    $lfechai=date("d/m/Y");

 if(isset($_POST["txtfechaf"]))
   $lfechaf=$_POST["txtfechaf"];
  else
    $lfechaf=date("d/m/Y");

  if(isset($_POST["ckchofer"]))
    $lunchofe=1;
  else
    $lunchofe=0;
  
  if(isset($_POST["txtmonto"]))
     $lmonto = $_POST["txtmonto"] ;
  else
    $lmonto=0;
  
  if(isset($_POST["cbchofer"]))
    $lchofer=$_POST["cbchofer"];
  else
    $lchofer="";
  
  ?>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
    <title>Sistemas de ventas de tiquetes</title> 
    <link rel="stylesheet" href="css/general.css" type="text/css" />
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="css/repchofer.css" type="text/css" />
  	<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
    <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
    <script type="text/javascript" src="js/sfunciones.js"></script>
  	<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
  	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
  	<script type="text/javascript">
      $(document).ready(function(){
     		$('#txtfechai').datepicker({
   			inline: true
  		});
  $('#txtfechai').datepicker('option', {dateFormat: 'dd/mm/yy'});

      $('#txtfechaf').datepicker({
   			inline: true
  		});

 $('#txtfechaf').datepicker('option', {dateFormat: 'dd/mm/yy'});

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
        <form name='formChofe' method='post' action='repchofer.php'>
          <div id='ckchoferes'>
            <tr>
              <td><input type="checkbox" name="ckchofer" id='ckchofer' value='1' <?php if($lunchofe==1) echo "checked"; ?> >Un chofer</td>
            </tr>
          </div>

          <div id='choferaf'>
          <tr>
            <td>Chofer</td>
          </tr>
         
          <tr>
          <td>
          <select id='cbchofer' name='cbchofer'>
          <?php

          $res=ListaActivos("Chofer",null);
          for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idchofer"]."'";
            if($res[$x]["idchofer"]==$lchofer)
                echo " selected ";
            
            echo">".$res[$x]["Nombre"]."</option>";
          }
          ?>
          </select>
          </td>
          </tr>
          </div>

          <div id='controles'>
            Desde: <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'><br/>
            Hasta: <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'> <br/>
            Comisión: <input class = 'monto' type="text" name='txtmonto' id='txtmonto' value='<?php echo $lmonto; ?>'> <br/>
            <input type='submit' value='Volver a calcular'>
            <input type='submit' value='Imprimir' onclick='window.print();'>
          </div>
          <div id='datos'>
            <div class='titulo1'>Reporte de choferes desde <?php echo $lfechai; ?> hasta el <?php echo $lfechaf; ?> </div>
            <table class='tablaChofe'>
              <thead>
                <tr>
                  <th><div align="left">Chofer</div></th>
                  <th><div align="left">Rutas</div></th>
                  <th><div align="right">Viajes</div></th>
                  <th><div align="right">A Pagar </div></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $dat=RepChoferes(fechatoUTC($lfechai),fechatoUTC($lfechaf),$lmonto,$lunchofe,$lchofer);
                  $tt_nviajes=0;
                  $tt_apagar=0.00;

                  for($x=0;$x<count($dat);$x++)
                  {

                   
                    if($dat[$x]["orden"] == 1)
                    {
                       echo "<tr>";
                      echo "<td>".$dat[$x]["chofer"]."</td>";
                      echo "<td>".$dat[$x]["ruta"]."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["nviajes"],0)."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["apagar"],2)."</td>";
                    echo "</tr>";
                     $tt_nviajes+= $dat[$x]["nviajes"];
                    $tt_apagar+= $dat[$x]["apagar"];
                    }
                    else
                      {
                      echo "<tr class='tot_Chofer'>";
                      echo "<td>Total por: ".$dat[$x]["cnombre"]." </td>";
                      
                      echo "<td></td>";
                      echo "<td class='monto'>". number_format($dat[$x]["nviajes"],0)."</td>";
                      echo "<td class='monto'>". number_format($dat[$x]["apagar"],2)."</td>";
                      echo "</tr>";
                  }

                   
                  }
                ?>
              </tbody>
                <tfoot id='tfoottr'>
                    <?php 
                      echo "<tr>";
                        echo "<td>TOTALES</td>";
                        echo "<td></td>";
                        echo "<td class='monto'>".number_format($tt_nviajes,0)."</td>";
                        echo "<td class='monto'>".number_format($tt_apagar,2)."</td>";
                      echo "</tr>";
                    ?>
                </tfoot>
            </table>
          </div>
        </form>
      </div>
    </div>
    <?php
      Pie();
    ?>
  </body>
</html>
