<?php
  include_once('controles.php');
  inicio("precios");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');

  if(isset($_POST["cbruta"]))
    $lruta=$_POST["cbruta"];
  else
    $lruta=1;

  if(isset($_POST["cbproducto"]))
    $lproducto=$_POST["cbproducto"];
  else
    $lproducto=1;

if(isset($_GET["do"]) && $_GET["do"]=="guardar"){
  
  $matriz = explode("!",$_POST["lacadena"]);
  $lmatriz= Array();
  for($x=0;$x<count($matriz);$x++){
     if($matriz[$x]!="")
      array_push($lmatriz, explode(",",$matriz[$x]));
  }
 
  
  ActuaPrecioMatriz($lruta,$lmatriz);
}

?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/precios.css" type="text/css" />
     		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
     		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
    <script type="text/javascript" src="js/precios.js"></script>
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
    $(document).ready(function() {
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
<div class='divprincipal'>
<form name='formsegu' method='post' action='precios.php'>
<h1>Asignaci&oacute;n de paradas y precios a rutas</h1>
 <div id='controles'>
  Ruta : <select id='cbruta' name='cbruta' onchange='traer();'>
        <?php
        $res=ListaActivos("Ruta",null);
        
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idruta"]."'";
             if($res[$x]["idruta"]==$lruta)
                echo " selected ";
            echo">".$res[$x]["nombre"]."</option>";
            }
        ?>
    </select>
    Producto : <select id='cbproducto' name='cbproducto' onchange='traer();'>
        <?php
        $res=ListaActivos("Producto",null);
        
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idProducto"]."'";
             if($res[$x]["idProducto"]==$lproducto)
                echo " selected ";
            echo">".$res[$x]["nombre"]."</option>";
            }
        ?>
    </select>
     <input type='button' value='Guardar Cambios' onclick='GuardarDatos();'>
 </div>
 <div>
  <HR style='width:100%'></HR>
 </div>
  <div id='detalle'>
    <table id='tablamarca'>
       <thead>
       <tr>
       <th>Parada abordaje</th><th></th><th>Parada llegada</th><th>Precio</th>
       </tr>
       </thead>
       <tbody>
       <?php
        $dat=ListaPrecios($lruta,$lproducto);
        $paradas = ListaActivos("Parada",null);
        for($x=0;$x<count($dat);$x++){
           echo "<tr>";
           echo "<td><input type='hidden' id='txtparada1' value='".$dat[$x]["idparada1"]."' >".$dat[$x]["parada1"]."</td>";
           echo "<td><img src='img/flechaderecharoja.png' style='width:20px;'></td>";
           echo "<td><input type='hidden' id='txtparada2' value='".$dat[$x]["idparada2"]."' >".$dat[$x]["parada2"]."</td>";
           echo "<td><input type='number' step='any' id='txtprecio' style='text-align:right;' value='".$dat[$x]["precio"]."'></td>";
           echo "</tr>";
        }
         for($x=0;$x<10;$x++){
           echo "<tr>";
           echo "<td><select id='txtparada1' >";
           echo "<option value='0' selected >Ninguno</option>";
           for($y=0;$y<count($paradas);$y++){
            echo "<option value='".$paradas[$y]["idparada"]."' >".$paradas[$y]["nombre"]."</option>";

           }
           echo "</select></td>";
           echo "<td><img src='img/flechaderecharoja.png' style='width:20px;'></td>";
           echo "<td><select id='txtparada2' >";
           echo "<option value='0' selected >Ninguno</option>";
           for($y=0;$y<count($paradas);$y++){
            echo "<option value='".$paradas[$y]["idparada"]."' >".$paradas[$y]["nombre"]."</option>";

           }
           echo "</select></td>";
           echo "<td><input type='number' step='any' id='txtprecio' style='text-align:right;' value='0'></td>";
           echo "</tr>";
        }
       ?>
       </tbody>
    </table>
  </div>
  <div>
   
  </div>
</form>
</div>
</div>
</div>
<?php
  Pie();
   
?>


</body>
</html>

