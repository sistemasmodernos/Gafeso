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
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/lconseenc.css" type="text/css" />
    		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
  		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
          <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>                
        <script type="text/javascript" src="js/reimtiq.js"></script>
        <script type="text/javascript" src="js/lconseenc.js"></script>
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

<form name='formlasiento' id='formlasiento' method='post' action='lconseenc.php'>
<div id='controles'>
       Horarios desde: <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'> 
       Hasta: <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'>
       <br/>
       <input type="radio" 
              name="tipofecha" 
              value="fecha" 
              <?php echo ($tipofecha=='fecha')? "checked": ""; ?> > Fecha de la Encomienda <br/>
       <input type="radio" 
              name="tipofecha" 
              value="fechafe"
              <?php echo ($tipofecha=='fechafe')? "checked": "" ; ?> > Fecha Factura    <br/>
              <br/>
        <span class="txtform">Enviar el reporte a Excel: </span>
        <input type="checkbox" name="excel" id="excel" value="1"  />
        <br /><br/>
       <p>
       Impresora: <select id='cbimpresora' name='cbimpresora' onchange='cambiaimpresora(this.value);'>
        <?php
        $res=ListaActivos("Impresora",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idimpresora"]."'";
            if($res[$x]["idimpresora"]==$limpresora)
                echo " selected ";
            echo">".$res[$x]["nombre"]."</option>";
            }
        ?>
    </select>
         <br/>
         <br/><input type='submit' name="Enviar" id="Enviar" value='Volver a cargar los datos' >
         <br/>
         <br/> <input type='button' value='imprimir' onclick='window.print();'>
</div>
<div id='divdatos'>
<div id='divtitulo'>
      <p> Consecutivo de Encomiendas <br/> Impresora 
      <?php 
       $dat=ListaActivos("Impresora",array("idimpresora=".$limpresora));
       echo $dat[0]["nombre"];
      ?></p>
    </div>
<div id='divcuadro'>
<?php
       $dat= ListaActivos("Encomienda",array(
                  "e.".$tipofecha." between '".fechatoUTC($lfechai)." 00:00' and '".fechatoUTC($lfechaf)." 23:59' ",
                  "e.idimpresora=".$limpresora
                  )) ;
        $dat = ordenaMatriz($dat,"factura");
        $maxpag=30; //cantidad de líneas que caben en una página
       $cont=1;
       echo "<div class='divpag'><table class='tabasientos'>".
       "<thead><tr><th>Tiquete</th><th>F.viaje</th><th>Hora</th><th>Cliente</th><th>Usuario</th><th>Monto</th><th>IVA</th><th>Total</th><</tr></thead>";
       $par ='xxxx';
       $total=0;
       $totaliva=0;

       for($x=0;$x<count($dat);$x++){
           if($cont>=$maxpag)
           {
               $cont=1;
       echo "</table></div><div class='divpag'><table class='tabasientos'>".
       "<thead><tr><th>Tiquete</th><th>F.viaje</th><th>Hora</th><th>Cliente</th><th>Usuario</th><th>Monto</th><th>IVA</th><th>Total</th></tr></thead>";

            }
          
              
            echo "<tr><td>".$dat[$x]["factura"].
            "</td><td>".fechatoNatural(substr($dat[$x]["fechaviaje"],0,10)).
            "</td><td>".$dat[$x]["hora"].
           "</td><td>".$dat[$x]["remitente"].
           "</td><td>".$dat[$x]["usuario"];
           if($dat[$x]["estado"]==0){
              echo "</td><td class='cmonto'>".number_format($dat[$x]["monto"]-$dat[$x]["iva"],2);
              echo "</td><td class='cmonto'>".number_format($dat[$x]["iva"],2);
              echo "</td><td class='cmonto'>".number_format($dat[$x]["monto"],2);
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
              echo "<th>".number_format($total-$totaliva,2)."</th>";
              echo "<th>".number_format($totaliva,2)."</th>";
              echo "<th>".number_format($total,2)."</th>";
              echo "</tr></tfoot>";
       echo "</table></div>";
    ?>
</div>

</form>
</div>
</div>
<?php
  Pie();
   
?>


</body>
</html>

