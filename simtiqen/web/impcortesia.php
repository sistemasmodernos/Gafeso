<?php
  include_once('controles.php');
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  inicio("impcortesia");
    
 ?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
        <link rel="stylesheet" href="css/general.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />        
        <link type="text/css" href="css/impcortesia.css" rel="stylesheet" />       
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />        
        <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/impcortesia.js"></script>
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

<form id='fimpcortesia' name='fimpcortesia' action='impcortesia.php' method='post'>
   <h1>Impresi&oacute;n de tiquetes de cortes&iacute;a</h1>
    <div id='divanular'>
     <p class='mensaje' <?php if(trim($res)=="") echo "style='display:none;' "; ?> ><?php echo $res; ?></p>
       <input type='button' value='Imprimir seleccionados' onclick='imprimecor();'>
    </div>

    <table id='tablatiq' border='1px'>
       <thead>
       <tr>
          <th>Imprimir?</th>
            <th>Ruta</th>          
          <th>Viaje</th>
          <th>Tiquete</th>
          <th>Nombre</th>
          <th>Asiento</th>
          <th>Monto</th>
       </tr>
       </thead>
       <tbody>
       <?php 
         $filtros = array();
         array_push($filtros,"t.idproducto=99");
         array_push($filtros,"t.impreso=0");
         array_push($filtros,"t.estado=0");
         array_push($filtros,"t.factura!='APARTA' ");
          $lista = ListaActivos("Tiquete",$filtros);
         for($x=0;$x<count($lista);$x++){
             $asiento=$lista[$x]["asiento"];
             if($asiento==-1)
               $asiento='De Pie';
             echo "<tr>".
             "<td><input type='checkbox' name='ckmarca[]' value='".$lista[$x]["idtiquete"]."'> </td>".
             "<td>".$lista[$x]["ruta"]."</td>".
             "<td>".fechatoNatural(substr($lista[$x]["fechaviaje"],0,10))." ".$lista[$x]["hora"]."</td>".
             "<td>".$lista[$x]["factura"]."</td>".
             "<td>".$lista[$x]["anombre"]."</td>".
             "<td>".$asiento."</td>".
             "<td>".$lista[$x]["monto"]."</td>".
             "</tr>";
             
         }
       ?>
       <tbody>
    </table>
</form>
</div>
</div>
<?php
  Pie();
?>
</body>
</html>
