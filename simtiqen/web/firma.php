<?php
   include_once('controles.php');
    inicio("sinpermiso");
    $laestacion = $_GET["esta"];
    $res = EncomiendasSinFirma($laestacion);
?>

<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1"/> 
  <meta name="apple-mobile-web-app-capable" content="yes" />
<link type="text/css" href="css/firma.css" rel="stylesheet" />
<script type="text/javascript" src="js/firma.js"></script>
<script type="text/javascript" src="js/jquery-current.min.js"></script>
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
</script>
</head>
<body>
<div id="tituloenco">
   <table>
       <tr>
          <td>Encomienda</td><td><?php echo $res[0]["factura"]; ?> </td>
        </tr>
        <tr>
          <td>Remitente</td><td><?php echo $res[0]["remitente"]; ?> </td>
        </tr>
        <tr>
          <td>Destinatario</td><td><?php echo $res[0]["destinatario"]; ?> </td>
          
       </tr>
   </table>
</div>
Firme abajo <input type="button" id="Guardar" onclick='guardarfirma();' value="Guardar firma">
<div id='mainsignature' style="width:800px;height:350px;" >
<div id="signature"></div>
</div>
<form id="formfirma" method="POST" action="finfirma.php?esta=<?php echo $laestacion;  ?>"  >
   <input type="hidden" id="txtdatos" name="txtdatos">
   <input type="hidden" id="txtidencomienda" name="txtidencomienda" value ='<?php echo $res[0]["idencomienda"]; ?>'>
  </form> 
</body>
</html>
