<?php
   include_once('controles.php');
    inicio("sinpermiso");
    $laboleta = $_GET["boleta"];
    $res = CargaBoletaVaca($laboleta);
?>

<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1"/> 
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link type="text/css" href="css/firma.css" rel="stylesheet" />
	<script type="text/javascript" src="js/firma.js"></script>
	<script type="text/javascript" src="js/jquery-current.min.js"></script>
	<link type="text/css" href="css/planilla.css" rel="stylesheet" />     
	<!-- this, preferably, goes inside head element: -->
	<!--[if lt IE 9]>
	<script type="text/javascript" src="js/flashcanvas.js"></script>
	<![endif]-->
	<!-- you load jquery somewhere before jSignature ... -->
	<script src="js/jSignature.min.js"></script>
	<script>
		$(document).ready(function() {
			$("#signature").jSignature()
		})
		function borrarfirma(){
			$("#signature").jSignature("reset");
		}
	</script>
</head>
<body>
<div>
<table class="boleta">
    <tr>
		<td colspan="3" class="encas"><span class="titulo"><?php echo $res->compania; ?></span></td>
    </tr>
    <tr>
      <td colspan="3" class="encas"><span class="subtitulo">Boleta de Vacaciones</span></td>
    </tr>
    <tr>
      <td colspan="2"><span class="marca">Número: <?php echo $res->idmovi; ?></span></td>
      <td colspan="1">Fecha: <?php echo $res->fechadi; ?></td>
    </tr>
    <tr>
      <td colspan="2">Chofer: <b><?php echo $res->nombre; ?><b></td>
      <td colspan="1">Año: <?php echo $res->anno; ?></td>
    </tr>
    <tr>
      <td><span class="marca">Días: <?php echo $res->dias; ?></span></td>
      <td>Desde: <?php echo $res->fechaini; ?></td>
	  <td>Hasta: <?php echo $res->fechafin; ?></td>
    </tr>
    <tr>
      <td colspan="3">Razón: <?php echo $res->razon; ?></td>
    </tr>
</table>
<br>
<br>
</div>
Firme abajo <input type="button" id="Guardar" onclick='guardarfirma();' value="Guardar firma">
<br>			

		<div id='mainsignature' style="width:350px;height:100px;" >
			<div id="signature"></div>
		</div>
		<br>
<input type="button" id="Borrar" onclick='borrarfirma();' value="Limpiar">
<form id="formfirma" method="POST" action="finfirmavaca.php"  >
   <input type="hidden" id="txtdatos" name="txtdatos">
   <input type="hidden" id="txtidmovi" name="txtidmovi" value ='<?php echo $laboleta; ?>'>
  </form> 
</body>
</html>