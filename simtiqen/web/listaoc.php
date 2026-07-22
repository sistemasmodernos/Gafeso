<?php
  include_once('controles.php');
  inicio("ocompra");


 $canlineas= 20;
 $filtro="";
 if(isset($_POST["txtlineas"])){
     $canlineas=$_POST["txtlineas"];
     $filtro=$_POST["txtfiltro"];
}
if(isset($_GET["do"]))
{
    $ocompra = new Ocompra();
    $ocompra->idCompra= $_GET["id"];
    $ocompra->Cargar();
    $bit=NuevaBitacora();
    $ocompra->HeredaBitacora($bit);
    $ocompra->Anular();
    }
 $orden = new Ocompra();
 $lista=$orden->ListaActivos($filtro,$canlineas);

?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <link type="text/css" href="css/listaoc.css" rel="stylesheet" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
        <script type="text/javascript" src="js/listaoc.js"></script>
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
            $(function(){
                
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
<form method='post' id='formloc' action='listaoc.php'>
<h1>Ordenes de compra</h1>
<div id='divcontrol'>
Mostrar <input type='number' id='txtlineas'  style='width:75px;' name='txtlineas' value='<?php  echo $canlineas; ?>'> l&iacute;neas<br/>
Filtrar    <input typee='text' id='txtfiltro'  style='width:300px;'  name='txtfiltro' value='<?php  echo $filtro ?>' > <input type='submit' value='Volver a cargar'>
</div>
<div>
<p>
<a style='font-size:150%' href='ordencompra.php?id=0' >-->Crear nueva orden de compra</a>
</p>
<table id='tablaoc' >
  <thead>
     <tr>
        <th>ID</th>
        <th>Fecha</th>
        <th>Proveedor</th>
        <th>Monto</th>
        <th>Producto</th>
        <th>Acci&oacute;n</th>
     </tr>
  </thead>
  <tbody>
     <?php 
        for($x=0;$x<count($lista);$x++){
            echo "<tr>";
            echo"<td><a href='ordencompra.php?id=".$lista[$x]["idcompra"]."' > ".
                $lista[$x]["idcompra"]."</a></td>";
            echo "<td>".$lista[$x]["fecha"]."</td>";
            echo "<td>".$lista[$x]["proveedor"]."</td>";
            echo "<td class='monto'>".number_format($lista[$x]["total"],2)."</td>";
            echo "<td>".$lista[$x]["producto"]."</td>";
            echo "<td><a href='#' onclick='anular(".$lista[$x]["idcompra"].");'>Anular</a></td>";
            echo "</tr>";
        }
     ?>
  </tbody>
</table>
</div>
</form>

</div>
<?php
  Pie();
?>
</body>
</html>
