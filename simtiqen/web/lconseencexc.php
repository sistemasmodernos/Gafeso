<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=ConsecutivoEncomiendas.xls");
header("Pragma: no-cache");
header("Expires: 0");?>

<?php
  include_once('controles.php');
  inicio("repconseenco");
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

if(isset($_POST["cbimpresora"]))
    $limpresora=$_POST["cbimpresora"];
else
    $limpresora=$_SESSION["parametros"]["impresoradefault"];

if(isset($_POST["tipofecha"]))
  $tipofecha = $_POST["tipofecha"];
else
  $tipofecha = "fecha";
?>
	<div id='divtitulo'>
      <p> Consecutivo de Encomiendas <br/> Impresora 
		      	<?php 
		       		$dat=ListaActivos("Impresora",array("idimpresora=".$limpresora));
		       		echo $dat[0]["nombre"];
		        ?>
  		<br/>
  		Del <?php echo $lfechai . " al " . $lfechaf ?>
  		</p>
    </div>

  <?php
       $dat= ListaActivos("Encomienda",array(
                  "e.".$tipofecha." between '".fechatoUTC($lfechai)." 00:00' and '".fechatoUTC($lfechaf)." 23:59' ",
                  "e.idimpresora=".$limpresora
                  )) ;
        $dat = ordenaMatriz($dat,"factura");
       $cont=1;
       echo "<div class='divpag'><table class='tabasientos'>".
       "<thead><tr><th>Tiquete</th><th>F.viaje</th><th>Hora</th><th>Cliente</th><th>Usuario</th><th>Monto</th><th>IVA</th><th>Total</th></tr></thead>";
       $par ='xxxx';
       $total=0;
       $totaliva=0;

       for($x=0;$x<count($dat);$x++){
            echo "<tr><td>".$dat[$x]["factura"].
            "</td><td>".fechatoNatural(substr($dat[$x]["fechaviaje"],0,10)).
            "</td><td>".$dat[$x]["hora"].
           "</td><td>".$dat[$x]["remitente"].
           "</td><td>".$dat[$x]["usuario"];
           if($dat[$x]["estado"]==0){
             echo "</td><td class='cmonto'>".number_format($dat[$x]["monto"]-$dat[$x]["iva"],2,",",".");
              echo "</td><td class='cmonto'>".number_format($dat[$x]["iva"],2,",",".");
              echo "</td><td class='cmonto'>".number_format($dat[$x]["monto"],2,",",".");
              $total+=$dat[$x]["monto"];
              $totaliva+=$dat[$x]["iva"];
           }
           else{
            echo "</td><td>Anulada</td><td></td><td>";
           }
           echo "</td></tr>";
           
           $cont++;
       }
              echo "<tfoot><tr>";
              echo "<th colspan='5'>Totales</th>";
              echo "<th>".number_format($total-$totaliva,2,",",".")."</th>";
              echo "<th>".number_format($totaliva,2,",",".")."</th>";
              echo "<th>".number_format($total,2,",",".")."</th>";
              echo "</tr></tfoot>";
       echo "</table></div>";
?>
