<?php
  include_once('controles.php');
  inicio("rescierre");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');

 if(isset($_POST["txtfechai"]))
   $lfechai=$_POST["txtfechai"];
 else
   $lfechai=date("d/m/Y",time()-(60*60*24));

 if(isset($_POST["txtfechaf"]))
   $lfechaf=$_POST["txtfechaf"];
 else
   $lfechaf=date("d/m/Y",time()-(60*60*24));

$nomusu ="";

$totaltiq=0;
$totalrnc=0;
?>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/cierreenco.css" type="text/css" />
  <link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
  <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
  <script type="text/javascript" src="js/sfunciones.js"></script>
  <script type="text/javascript" src="js/cierreenco.js"></script>        
  <script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
  <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
  <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
  <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
  <script type="text/javascript" src="js/jquery.uitablefilter.js"></script>
  <script type="text/javascript">
  //Inicia todos los objetos de la p'agina
      $(function(){
          $('#txtfechai').datepicker({inline: true});
          $('#txtfechai').datepicker('option', {dateFormat: 'dd/mm/yy'});
          $('#txtfechaf').datepicker({inline: true});
          $('#txtfechaf').datepicker('option', {dateFormat: 'dd/mm/yy'});
          ajusta();
          theTable = $("#tablafiltro");
          $("#txtfiltro").keyup(function() {
              $.uiTableFilter(theTable, this.value);
          });
			});
  </script>
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
   Encabezado();
   
?>
<div class="wrap">
    <?php Menu(); ?>
    <div id='cuerpo' class='content'  >
    <form name='formcierre' id='formcierre' method='post' action='rescierre.php' >
          <div id='controles' style='float:left;'>
              <div style='float:left;'>
                  <p>
                    Desde: <input type='text' name='txtfechai' id='txtfechai' class='fecha' value ='<?php echo $lfechai; ?>'>
                    Hasta: <input type='text' name='txtfechaf' id='txtfechaf' class='fecha' value ='<?php echo $lfechaf; ?>'>
                  </p>
              </div>
          </div>
      <div class='botones'>
          <input type='submit' value='Volver a cargar los datos' >
      </div>
      <div id='datoscierre' >
          <div class="fechahora">
              Fecha y Hora de impresi&oacute;n: <span><?php echo Date('Y-m-d H:i:s'); ?></span>
              <br><br>
          </div>
          <?php
            $datcierre = CierreDeCierres(fechatoUTC($lfechai),fechatoUTC($lfechaf)) ;
            echo "<table class='tabasientos'>
               <thead><tr>
                    <th colspan='5'>&nbsp;</th>
                    <th colspan='2'>Tiquetes</th>
                    <th colspan='2'>Encomiendas</th>
                    <th colspan='2'>Totales</th>
                    </tr></thead>
                <thead>
                <tr>
                    <th>Estaci&oacute;n</th>
                    <th>Impresora</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Cierre</th>
                    <th>Cantidad</th>
                    <th>Monto</th>
                    <th>Cantidad</th>
                    <th>Monto</th>
                </tr>
                </thead>";
                $total=0;
                $laimp = "5655";
                $laest = "5655";
                $ciecantiq = 0;
                $ciemontiq = 0;
                $ciecanenc = 0;
                $ciemonenc = 0;
                $sincantiq = 0;
                $sinmontiq = 0;
                $sincanenc = 0;
                $sinmonenc = 0;
                while($lin = mysqli_fetch_array($datcierre)){
                    echo "<tr ";
                    if($lin["idcierre"] == 0)
                        echo " style='background-color:yellow;' ";
                    echo ">";
                    if ($lin["estacion"] != $laest || $lin["impresora"] != $laimp ){
                        echo "<td>".$lin["estacion"]."</td>";
                        echo "<td>".$lin["impresora"]."</td>";
                        echo "<td>".fechatoNormal($lin["fecha"])."</td>";
                        $laimp = $lin["impresora"];
                        $laest = $lin["estacion"];
                    }else{
                        echo "<td>&nbsp;</td>";
                        echo "<td>&nbsp;</td>";
                        echo "<td>&nbsp;</td>";
                    }
                    echo "<td>".$lin["nombrelargo"]."</td>";
                      if ($lin["idcierre"] == 0)
                      {
                          echo "<td>Sin Cierre</td>";
                      }else{
                          echo "<td>".$lin["idcierre"]."</td>";
                      }
                      if ($lin["cantiq"] == 0)
                      {
                          echo "<td>&nbsp</td>";
                      }else{
                          echo "<td style='text-align:right;'>".Number_format($lin["cantiq"],0)."</td>";
                      }
                      if ($lin["montiq"] == 0)
                      {
                          echo "<td>&nbsp</td>";
                      }else{
                          echo "<td style='text-align:right;'>".Number_format($lin["montiq"],2)."</td>";
                      }
                      if ($lin["canenc"] == 0)
                      {
                          echo "<td>&nbsp</td>";
                      }else{
                          echo "<td style='text-align:right;'>".Number_format($lin["canenc"],0)."</td>";
                      }
                      if ($lin["monenc"] == 0)
                      {
                          echo "<td>&nbsp</td>";
                      }else{
                          echo "<td style='text-align:right;'>".Number_format($lin["monenc"],2)."</td>";
                      }
                      echo "<td style='text-align:right;'>".Number_format($lin["cantiq"]+$lin["canenc"],0)."</td>";
                      echo "<td style='text-align:right;'>".Number_format($lin["montiq"]+$lin["monenc"],2)."</td>";

                      echo "</tr>";
                      if ($lin["idcierre"] == 0){
                          $sincantiq += $lin["cantiq"];
                          $sinmontiq += $lin["montiq"];
                          $sincanenc += $lin["canenc"];
                          $sinmonenc += $lin["monenc"];
                        }else{
                          $ciecantiq += $lin["cantiq"];
                          $ciemontiq += $lin["montiq"];
                          $ciecanenc += $lin["canenc"];
                          $ciemonenc += $lin["monenc"];
                        }
                }

                echo "<tr>";
                echo "<td colspan='5'><b>Totales</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sincantiq+$ciecantiq,0)."</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sinmontiq+$ciemontiq,2)."</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sincanenc+$ciecanenc,0)."</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sinmonenc+$ciemonenc,2)."</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sincantiq+$sincanenc+$ciecantiq+$ciecanenc,0)."</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sinmontiq+$sinmonenc+$ciemontiq+$ciemonenc,2)."</b></td>";
                echo "</tr></b>";
               echo "</table>";
               echo "<h2>TOTALES</h2>
               <table class='tabasientos'>
               <thead><tr>
                    <th>&nbsp;</th>
                    <th colspan='2'>Tiquetes</th>
                    <th colspan='2'>Encomiendas</th>
                    <th colspan='2'>Totales</th>
                    </tr></thead>
               <thead><tr>
                    <th>Cierre</th>
                    <th>Cantidad</th>
                    <th>Monto</th>
                    <th>Cantidad</th>
                    <th>Monto</th>
                    <th>Cantidad</th>
                    <th>Monto</th>
                    </tr></thead>";
                echo "<tr style='background-color:yellow;'>";
                echo "<td>Sin Cierre</td>";
                echo "<td style='text-align:right;'>".Number_format($sincantiq,0)."</td>";
                echo "<td style='text-align:right;'>".Number_format($sinmontiq,2)."</td>";
                echo "<td style='text-align:right;'>".Number_format($sincanenc,0)."</td>";
                echo "<td style='text-align:right;'>".Number_format($sinmonenc,2)."</td>";
                echo "<td style='text-align:right;'>".Number_format($sincantiq+$sincanenc,0)."</td>";
                echo "<td style='text-align:right;'>".Number_format($sinmontiq+$sinmonenc,2)."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>Cerrado</td>";
                echo "<td style='text-align:right;'>".Number_format($ciecantiq,0)."</td>";
                echo "<td style='text-align:right;'>".Number_format($ciemontiq,2)."</td>";
                echo "<td style='text-align:right;'>".Number_format($ciecanenc,0)."</td>";
                echo "<td style='text-align:right;'>".Number_format($ciemonenc,2)."</td>";
                echo "<td style='text-align:right;'>".Number_format($ciecantiq+$ciecanenc,0)."</td>";
                echo "<td style='text-align:right;'>".Number_format($ciemontiq+$ciemonenc,2)."</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td><b>Totales</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sincantiq+$ciecantiq,0)."</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sinmontiq+$ciemontiq,2)."</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sincanenc+$ciecanenc,0)."</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sinmonenc+$ciemonenc,2)."</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sincantiq+$sincanenc+$ciecantiq+$ciecanenc,0)."</b></td>";
                echo "<td style='text-align:right;'><b>".Number_format($sinmontiq+$sinmonenc+$ciemontiq+$ciemonenc,2)."</b></td>";
                echo "</tr></b>";

               echo "</table></div>";
            ?>
      </div>
    </form>
    </div>
<?php
  Pie();
?>
</body>
</html>
