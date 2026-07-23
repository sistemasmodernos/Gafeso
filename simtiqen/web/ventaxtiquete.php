<?php
  include_once('controles.php');
  inicio("ventaxtiquete");
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

$filtros=" ";
$lruta="";
$lproducto="";
$lsalida="";
$lllegada="";

if(isset($_POST["cbruta"]) && $_POST["cbruta"]!=""){
  $filtros.=" and v.idruta= ".$_POST["cbruta"];
  $lruta =$_POST["cbruta"];
}

if(isset($_POST["cbproducto"]) && $_POST["cbproducto"]!=""){
  $filtros.=" and t.idproducto= ".$_POST["cbproducto"];
  $lproducto =$_POST["cbproducto"];
}

if(isset($_POST["cbsalida"]) && $_POST["cbsalida"]!=""){
  $filtros.=" and t.idparada1= ".$_POST["cbsalida"];
  $lsalida =$_POST["cbsalida"];
}

if(isset($_POST["cbllegada"]) && $_POST["cbllegada"]!=""){
  $filtros.=" and t.idparada2= ".$_POST["cbllegada"];
  $lllegada =$_POST["cbllegada"];
}

$resruta=ListaActivos("Ruta",null);
$resproducto=ListaActivos("Producto",null);
$resparada=ListaActivos("Parada",null);

//Devuelve el nombre de un registro de la lista segun su id
function BuscaNombre($plista,$pcampo,$pvalor,$pnombre){
  for($x=0;$x<count($plista);$x++)
    if($plista[$x][$pcampo]==$pvalor)
      return $plista[$x][$pnombre];
  return "";
}

//Describe el rango de fechas en texto natural
function DescribeFechas($pfechai,$pfechaf){
  $meses=array(1=>'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio',
               'Agosto','Septiembre','Octubre','Noviembre','Diciembre');
  $di=DateTime::createFromFormat('d/m/Y',$pfechai);
  $df=DateTime::createFromFormat('d/m/Y',$pfechaf);
  if(!$di || !$df)
    return "Del ".$pfechai." al ".$pfechaf;
  $d1=(int)$di->format('j'); $m1=(int)$di->format('n'); $a1=(int)$di->format('Y');
  $d2=(int)$df->format('j'); $m2=(int)$df->format('n'); $a2=(int)$df->format('Y');
  if($d1==$d2 && $m1==$m2 && $a1==$a2)
    return $d1." de ".$meses[$m1]." ".$a1;
  if($d1==1 && $d2==(int)$df->format('t')){
    if($m1==$m2 && $a1==$a2)
      return $meses[$m1]." ".$a1;
    if($a1==$a2)
      return $meses[$m1]." - ".$meses[$m2]." ".$a1;
    return $meses[$m1]." ".$a1." - ".$meses[$m2]." ".$a2;
  }
  return "Del ".$pfechai." al ".$pfechaf;
}

$subtitulo = DescribeFechas($lfechai,$lfechaf);
if($lruta!="")
  $subtitulo .= ", ruta ".BuscaNombre($resruta,"idruta",$lruta,"nombre");
if($lproducto!="")
  $subtitulo .= ", tiquete ".BuscaNombre($resproducto,"idProducto",$lproducto,"nombre");
if($lsalida!="")
  $subtitulo .= " saliendo de ".BuscaNombre($resparada,"idparada",$lsalida,"nombre");
if($lllegada!="")
  $subtitulo .= " llegando a ".BuscaNombre($resparada,"idparada",$lllegada,"nombre");

?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>Sistemas de ventas de tiquetes</title>
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/ventageneral.css" type="text/css" />
    		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
        <script type="text/javascript" src="js/reimtiq.js"></script>
  		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/jquery.table2excel.min.js"></script>
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

   function enviaexcel(){

    $("#tabladatosventas").table2excel({
    exclude: ".excludeThisClass",
    name: "Venta por Tiquete",
    filename: "ventaxtiquete" //do not include extension
    });
   }
		</script>
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
   Encabezado();

?>
<div class="wrap">
<?php Menu(); ?>

<div id='cuerpo' class='content'>

<form name='formventaxtiq' method='post' action='ventaxtiquete.php'>
<div id='controles'>
       Ventas desde:
         <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'>

       Hasta:
    <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'>

    <br/>
    <div class="filtro">
       <label for='cbruta' >Ruta: </label>
       <select id='cbruta' name='cbruta' >
        <option value=''>Todas</option>
        <?php
        for($x=0;$x<count($resruta);$x++){
            echo "<option value='". $resruta[$x]["idruta"]."'";
            if($resruta[$x]["idruta"]==$lruta)
                echo " selected ";
            echo">".$resruta[$x]["nombre"]."</option>";
            }
        ?>
       </select>
    </div>
    <div class="filtro">
       <label for='cbproducto' >Tiquete: </label>
       <select id='cbproducto' name='cbproducto' >
        <option value=''>Todos</option>
        <?php
        for($x=0;$x<count($resproducto);$x++){
            echo "<option value='". $resproducto[$x]["idProducto"]."'";
            if($resproducto[$x]["idProducto"]==$lproducto)
                echo " selected ";
            echo">".$resproducto[$x]["nombre"]."</option>";
            }
        ?>
       </select>
    </div>
    <div class="filtro">
       <label for='cbsalida' >Salida: </label>
       <select id='cbsalida' name='cbsalida' >
        <option value=''>Todas</option>
        <?php
        for($x=0;$x<count($resparada);$x++){
            echo "<option value='". $resparada[$x]["idparada"]."'";
            if($resparada[$x]["idparada"]==$lsalida)
                echo " selected ";
            echo">".$resparada[$x]["nombre"]."</option>";
            }
        ?>
       </select>
    </div>
    <div class="filtro">
       <label for='cbllegada' >Llegada: </label>
       <select id='cbllegada' name='cbllegada' >
        <option value=''>Todas</option>
        <?php
        for($x=0;$x<count($resparada);$x++){
            echo "<option value='". $resparada[$x]["idparada"]."'";
            if($resparada[$x]["idparada"]==$lllegada)
                echo " selected ";
            echo">".$resparada[$x]["nombre"]."</option>";
            }
        ?>
       </select>
    </div>

         <br/><input type='submit' value='Volver a cargar los datos' >
         <br/> <input type='button' value='imprimir' onclick='window.print();'>
         <br/> <input type='button' value='Generar Excel' onclick='enviaexcel();'>
</div>
<div id='divdatos'>
<div id='divtitulo'>
       <h1 style='text-align:center;'><?php echo DevParametro('nombrecia',3); ?><br/>Venta por Tiquete </h1>
       <p style='font-size:120%;font-weight:normal;'><?php echo $subtitulo; ?></p>
    </div>
<div id='divcuadro'>
    <?php
       $cabezatabla = "<div class='divpag'><table id='tabladatosventas' class='tabasientos'>".
       "<thead>
         <tr>
           <th>Ruta</th>
           <th>Salida</th>
           <th>Llegada</th>
           <th>Tiquete</th>
           <th>Cantidad</th>
           <th>Promedio</th>
           <th>Total</th>
          </tr>
        </thead>";
       $dat= VentaxTiquete(fechatoUTC($lfechai),fechatoUTC($lfechaf),$filtros) ;
        $maxpag=42; //cantidad de líneas que caben en una página
        $veces=1;
       $cont=1;
       echo $cabezatabla;
       $total=0;
       $cuenta=0;
       for($x=0;$x<count($dat);$x++){
           if($cont>=$maxpag)
           {
              $cont=1;
              if($veces>1)
                $maxpag=50; //cantidad de líneas que caben en una página
              $veces++;

            }
            echo "<tr><td>".$dat[$x]["ruta"].
            "</td><td>".$dat[$x]["salida"].
            "</td><td>".$dat[$x]["llegada"].
            "</td><td>".$dat[$x]["tiquete"].
            "</td><td class='cmonto'>".number_format($dat[$x]["cantidad"],0).
            "</td><td class='cmonto'>".number_format($dat[$x]["promedio"],2).
            "</td><td class='cmonto'>".number_format($dat[$x]["total"],2).
            "</td></tr>";
           $total=$total+$dat[$x]["total"];
           $cuenta=$cuenta+$dat[$x]["cantidad"];
           $cont++;
       }
       echo "<tfoot><tr><th colspan='4'>Total</th>".
       "<th class='cmonto'>".number_format($cuenta,0)."</th>".
       "<th class='cmonto'>".($cuenta>0 ? number_format($total/$cuenta,2) : "")."</th>".
       "<th class='cmonto'>".number_format($total,2)."</th></tr></tfoot>";
       echo "</table></div>";
    ?>
</div>
</div>
</div>
</form>
</div>
<?php
  Pie();

?>


</body>
</html>
