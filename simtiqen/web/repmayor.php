<?php
  include_once('controles.php');
  inicio("repmayor");
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
  <link rel="stylesheet" href="css/repmayor.css" type="text/css" />
    		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
  		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
          <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
            $(function(){
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
<form name='formd151' method='post' action='repmayor.php'>
<div id='controles'>
Desde: <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'><br/>
Hasta: <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'> <br/>
<input type='submit' value='Volver a calcular'>
<input type='submit' value='Imprimir' onclick='window.print();'>
</div>
<div id='datos'>
    <div class='titulo1'>Reporte Adulto Mayor desde el <?php echo $lfechai; ?> hasta el <?php echo $lfechaf; ?> </div>
    <table class='tablad151'>
        <thead>
        <tr>
          <th>C&eacute;dula</th><th>Nombre</th><th>Edad</th><th>Cantidad</th><th>Monto</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $dat=AdultoMayor(fechatoUTC($lfechai),fechatoUTC($lfechaf));
         $tcantidad=0;
         $tmonto=0;
         for($x=0;$x<count($dat);$x++)
         {
             echo "<tr>";
             echo "<td>".$dat[$x]["cedula"]."</td>";
             echo "<td>".$dat[$x]["nombre"]."</td>";
             echo "<td>".$dat[$x]["edad"]."</td>";
             echo "<td>".$dat[$x]["cantidad"]."</td>";
             echo "<td class='monto'>". number_format($dat[$x]["monto"],2)."</td>";
             echo "</tr>";
             $tcantidad+=$dat[$x]["cantidad"];
             $tmonto+=$dat[$x]["monto"];
             }
        ?>
        </tbody>
        <tfoot>
         <tr>
           <th colspan='3'>Total</th><th><?php echo $tcantidad; ?><th><?php echo number_format($tmonto,2); ?></th>
         </tr>
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
