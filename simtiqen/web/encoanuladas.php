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

<form name='formlasiento' method='post' action='encoanuladas.php'>
<div id='controles'>
       Horarios desde: <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'> 
       Hasta: <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'>
       <br/>
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
    
         <br/><input type='submit' value='Volver a cargar los datos' >
         <br/> <input type='button' value='imprimir' onclick='window.print();'>
</div>
<div id='divdatos'>
<div id='divtitulo'>
      <p> Encomiendas Anuladas <br/> Impresora 
      <?php 
       $dat=ListaActivos("Impresora",array("idimpresora=".$limpresora));
       echo $dat[0]["nombre"];
      ?></p>
    </div>
<div id='divcuadro'>
    <?php
       $dat= EncomiendasAnuladas(array(
                  "e.fechanc between '".fechatoUTC($lfechai)."T00:00:00' and '".fechatoUTC($lfechaf)."T23:59:59' ",
                  "e.idimpresora=".$limpresora
                  )) ;
        $dat = ordenaMatriz($dat,"factura");
        $maxpag=30; //cantidad de líneas que caben en una página
       $cont=1;
       echo "<div class='divpag'><table class='tabasientos'>".
       "<thead><tr><th>Tiquete</th><th>F.anulada</th><th>F.viaje</th><th>Hora</th><th>Cliente</th><th>Monto</th><th>NC</th><th>Razon</th></tr></thead>";
       $par ='xxxx';
       $total=0;
       for($x=0;$x<count($dat);$x++){
           if($cont>=$maxpag)
           {
               $cont=1;
       echo "</table></div><table class='tabasientos'>".
       "<thead><tr><th>Tiquete</th><th>F.anulada</th><th>F.viaje</th><th>Hora</th><th>Cliente</th><th>Monto</th><th>NC</th><th>Razon</th></tr></thead>";

            }
          
              
            echo "<tr><td>".$dat[$x]["factura"].
            "</td><td>".$dat[$x]["fechanc"].
            "</td><td>".fechatoNatural(substr($dat[$x]["fechaviaje"],0,10)).
            "</td><td>".$dat[$x]["hora"].
            "</td><td>".$dat[$x]["remitente"].
            "</td><td class='cmonto'>".number_format($dat[$x]["monto"],2).
            "</td><td>".$dat[$x]["numeronc"].
            "</td><td>".$dat[$x]["razon"].
            "</td></tr>";
              $total+=$dat[$x]["monto"];           
           $cont++;
       }
              echo "<tfoot><tr><th colspan='6'>Total</th><th>".number_format($total,2)."</th></tr></tfoot>";
       echo "</table></div>";
    ?>
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

