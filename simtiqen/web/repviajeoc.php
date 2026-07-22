<?php
  include_once('controles.php');
  inicio("repviajeoc");
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
    
?>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
    <title>Sistemas de ventas de tiquetes</title> 
    <link rel="stylesheet" href="css/general.css" type="text/css" />
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="css/repviajeoc.css" type="text/css" />
  	<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
    <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
    <script type="text/javascript" src="js/sfunciones.js"></script>
  	<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
  	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
    <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
  	<script type="text/javascript">
$.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '<Ant',
 nextText: 'Sig>',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);

      $(function(){
     		$('#txtfechai').datepicker({
   			inline: true
  		});
    //  $("#txtfechai").datepicker($.datepicker.regional['es']);
    //  $('#txtfechai').datepicker('option', {dateFormat: 'dd/mm/yy'});

      $('#txtfechaf').datepicker({
   			inline: true
  		});
     // $("#txtfechaf").datepicker($.datepicker.regional['es']);
     // $('#txtfechaf').datepicker('option', {dateFormat: 'dd/mm/yy'});
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
        <form name='formd151' method='post' action='repviajeoc.php'>
          <div id='controles'>
            Desde: <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'><br/>
            Hasta: <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'> <br/>
            <input type='submit' value='Volver a calcular'>
            <input type='submit' value='Imprimir' onclick='window.print();'>
          </div>
          <div id='datos'>
            <div class='titulo1'>Reporte de Estadistica desde <?php echo $lfechai; ?> hasta el <?php echo $lfechaf; ?> </div>
            <table class='tablad151'>
              <thead>
                <tr>
                  <th scope="col"><div align="center">Rutas</div></th>
                  <th colspan="4" scope="col"><div align="center">Normales</div></th>
                  <th colspan="4" scope="col"><div align="center">Extras</div></th>
                  <th colspan="4" scope="col"><div align="center">Total</div></th>
                </tr>
                <tr>
                  <th>&nbsp;</th>
                  <th>Viajes</th>
                  <th>Espacios</th>
                  <th>Personas</th>
                  <th>Ocupacion</th>
                  <th>Viajes</th>
                  <th>Espacios</th>
                  <th>Personas</th>
                  <th>Ocupacion</th>
                  <th>Viajes</th>
                  <th>Espacios</th>
                  <th>Personas</th>
                  <th>Ocupacion</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $dat=RepEsta1(fechatoUTC($lfechai),fechatoUTC($lfechaf));
                  $tn_viajes   =0;
                  $tn_espacios =0;
                  $tn_personas =0; 
                  $tn_ocupacion=0.00;
                  $te_viajes   =0;
                  $te_espacios =0;
                  $te_personas =0;
                  $te_ocupacion=0.00;
                  $tt_viajes   =0;
                  $tt_espacios =0;
                  $tt_personas =0;
                  $tt_ocupacion=0.00;

                  for($x=0;$x<count($dat);$x++)
                  {
                    echo "<tr>";
                    echo "<td>".$dat[$x]["nombre"]."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["viajes"],0)."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["espacios"],0)."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["personas"],0)."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["ocupacion"],2).'%'. "</td>";

                    echo "<td class='monto'>". number_format($dat[$x]["e_viajes"],0)."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["e_espacios"],0)."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["e_personas"],0)."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["e_ocupacion"],2).'%'. "</td>";

                    echo "<td class='monto'>". number_format($dat[$x]["t_viajes"],0)."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["t_espacios"],0)."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["t_personas"],0)."</td>";
                    echo "<td class='monto'>". number_format($dat[$x]["t_ocupacion"],2).'%'. "</td>";
                    echo "</tr>";

                    $tn_viajes   += $dat[$x]["viajes"];
                    $tn_espacios += $dat[$x]["espacios"];
                    $tn_personas += $dat[$x]["personas"];
                    $tn_ocupacion+= $dat[$x]["ocupacion"];
                    $te_viajes   += $dat[$x]["e_viajes"];
                    $te_espacios += $dat[$x]["e_espacios"];
                    $te_personas += $dat[$x]["e_personas"];
                    $te_ocupacion+= $dat[$x]["e_ocupacion"];
                    $tt_viajes   += $dat[$x]["t_viajes"];
                    $tt_espacios += $dat[$x]["t_espacios"];
                    $tt_personas += $dat[$x]["t_personas"];
                    $tt_ocupacion+= $dat[$x]["t_ocupacion"];
                  }
                ?>
              </tbody>
                <tfoot id='tfoottr'>
                    <?php 
                      echo "<tr>";
                         echo "<td>TOTALES</td>";

                         echo "<td class='monto'>".number_format($tn_viajes,0)."</td>";
                         echo "<td class='monto'>".number_format($tn_espacios,0)."</td>";
                         echo "<td class='monto'>".number_format($tn_personas,0)."</td>";
                         echo "<td class='monto'>".number_format( ($tn_personas/$tn_espacios)*100 ,2).'%'. "</td>";

                         echo "<td class='monto'>".number_format($te_viajes,0)."</td>";
                         echo "<td class='monto'>".number_format($te_espacios,0)."</td>";
                         echo "<td class='monto'>".number_format($te_personas,0)."</td>";
                         echo "<td class='monto'>".number_format( ($te_personas/$te_espacios)*100 ,2).'%'. "</td>";

                         echo "<td class='monto'>".number_format($tt_viajes,0)."</td>";
                         echo "<td class='monto'>".number_format($tt_espacios,0)."</td>";
                         echo "<td class='monto'>".number_format($tt_personas,0)."</td>";
                         echo "<td class='monto'>".number_format( ($tt_personas/$tt_espacios)*100 ,2).'%'. "</td>";
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
