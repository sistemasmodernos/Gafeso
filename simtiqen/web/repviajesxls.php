<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Carreras_por_dia.xls");
header("Pragma: no-cache");
header("Expires: 0");

include_once('lib/nucleo.php');
include_once('lib/utiles.php');
if(isset($_GET["fechai" ]))
  $lfechai=$_GET["fechai" ];
else
  $lfechai=date("d/m/Y",time());

if(isset($_GET["fechaf" ]))
  $lfechaf=$_GET["fechaf" ];
else
  $lfechaf=date("d/m/Y",time());


$dat= ListaCarreras(fechatoUTC($lfechai),fechatoUTC($lfechaf)) ;

?>


<table class='tabasientos'>
                <thead>
                <tr>
                  <th>D&iacute;a</th>
                  <th>Mes</th>
                  <th>A&ntilde;o</th>
                  <th>Unidad</th>
                  <th>Unidad</th>
                  <th>Cantidad Total Pasajeros</th>
                  <th>Cantidad Pasajeros Adulto Mayor</th>
                  <th>Pasajero Equivalente</th>
                  <th>Carreras</th>
                  <th>Ingresos</th>
                </tr>
              </thead>
              <tbody>
<?php
for($x=0;$x<count($dat);$x++){
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
                <td class='cmonto'  style=\"mso-number-format:'#,##0.00';\">".$dat[$x]["monto"]."</td>
                </tr>";
       }

       ?>
              </tbody>

              </table>
