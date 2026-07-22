<?php
  include_once('controles.php');
  inicio("lcortesia");
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

if(isset($_POST["txtnombre"]))
 $lnombre    = $_POST["txtnombre"];
else
 $lnombre="";
 
 if(isset($_POST["rbtipo"]))
 $reptipo    = $_POST["rbtipo"];
else
 $reptipo="cortesia";
    
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/lreimpre.css" type="text/css" />
    		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
        
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
<form name='formlasiento' method='post' action='lcortesia.php'>
<div id='controles'>
       Desde: <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'> 
       Hasta: <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'>
       <br/>
       Filtrar por este nombre: <input type='text' id='txtnombre' name='txtnombre' value='<?php echo $lnombre; ?>' >
       <br/>
       <br/>
       Listar: <br/>
        <input type="radio" name="rbtipo" value="cortesia"
        <?php if ($reptipo=="cortesia") echo "checked"; ?>
        > Cortesias<br>
        <input type="radio" name="rbtipo" value="nulo"
        <?php if ($reptipo=="nulo") echo "checked"; ?>
        > Tiquetes Nulos<br>
        <br/>
         <br/><input type='submit' value='Volver a cargar los datos' >
         <br/> <input type='button' value='imprimir' onclick='window.print();'>
</div>
<div id='divdatos'>
  <div id='divtitulo'>
      <?php if ($reptipo=="cortesia") echo "<p>Listado de Cortes&iacute;as</p>";
            else echo "<p>Listado de Tiquetes Nulos</p>"; ?>
  </div>
<div id='divcuadro'>
    <table id='tabladatos'>
      <thead>
         <tr>
            <th>Usuario </th>
             <th>Tiquete</th>
            <th>Fecha Tiquete</th>
            <th>Nombre del pasajero</th>
            <th>Viaje </th>
            <th>Asiento</th>
            <th>Comentario</th>
            
         </tr>
      </thead>
      <tbody>
   <?php 
       if($lnombre=='')
         $condi="";
        else
         $condi=" and upper(t.anombre) like '%".strtoupper(trim($lnombre))."%' ";
        if ($reptipo=="cortesia") 
            $dat = ListaCortesia(fechatoUTC($lfechai),fechatoUTC($lfechaf),$condi);
        else
            $dat = listaNulo(fechatoUTC($lfechai),fechatoUTC($lfechaf),$condi);

       for($x=0;$x<count($dat);$x++){
           echo "<tr>";
           echo " <td> ".$dat[$x]["nombre"]."</td>";
           echo " <td> ".$dat[$x]["factura"]."</td>";
           echo " <td> ".substr($dat[$x]["fechatiq"],0,10)."</td>";
           echo " <td> ".$dat[$x]["anombre"]."</td>";
           echo " <td> ".substr($dat[$x]["fechaviaje"],0,10)." ".$dat[$x]["horaini"]."</td>";
           echo " <td> ".$dat[$x]["asiento"]."</td>";
           echo " <td> ".$dat[$x]["comentario"]."</td>";
           echo " </tr> ";
        }
 
   ?>
    </tbody>
    <tfoot>
      <tr><th colspan='7'> Cantidad de tiquetes <?php echo count($dat) ?></th></tr>
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

