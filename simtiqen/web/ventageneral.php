<?php
  include_once('controles.php');
  inicio("vtageneral");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
 if(isset($_POST["txtfechai" ]))
    $lfechai=$_POST["txtfechai" ];
 else
    $lfechai=date("d/m/Y",time());
/*    
if(isset($_POST["txthorai"]))
    $lhorai=$_POST["txthorai"];
else
   $lhorai='05:00';

if(isset($_POST["txthoraf"]))
    $lhoraf=$_POST["txthoraf"];
else
   $lhoraf='19:00';
*/

if(isset($_POST["txtfechaf" ]))
    $lfechaf=$_POST["txtfechaf" ];
 else
    $lfechaf=date("d/m/Y",time());

$filtros=" ";
$limpresora="";
$lusuario="";
$lsocio="";
$lproducto="";
$ltipoviaje="";
$lparada="";

if(isset($_POST["cbimpresora"]) && $_POST["cbimpresora"]!=""){
  $filtros.=" and t.idimpresora= ".$_POST["cbimpresora"];
  $limpresora =$_POST["cbimpresora"];
}

if(isset($_POST["cbusuario"]) && $_POST["cbusuario"]!=""){
  $filtros.=" and t.idusuario= ".$_POST["cbusuario"];    
  $lusuario = $_POST["cbusuario"];
}

if(isset($_POST["cbsocio"]) && $_POST["cbsocio"]!=""){
  $filtros.=" and b.idsocio= ".$_POST["cbsocio"];    
  $lsocio = $_POST["cbsocio"];
}

if(isset($_POST["cbproducto"]) && $_POST["cbproducto"]!=""){
  $filtros.=" and t.idproducto= ".$_POST["cbproducto"];    
  $lproducto =$_POST["cbproducto"];
}

if(isset($_POST["cbtipoviaje"]) && $_POST["cbtipoviaje"]!=""){
  $filtros.=" and v.idtipoviaje= ".$_POST["cbtipoviaje"];    
  $ltipoviaje=$_POST["cbtipoviaje"];
}

if(isset($_POST["cbparada"]) && $_POST["cbparada"]!=""){
  $filtros.=" and t.idparada2= ".$_POST["cbparada"];
  $lparada = $_POST["cbparada"];    
}

if(isset($_POST["cbfuturo"]) && $_POST["cbfuturo"]!=""){
  $lfuturo = $_POST["cbfuturo"];    
  if($lfuturo=='1')
    $filtros.=" and concat(concat(v.fecha,' '),v.horaini) > '".date('Y-m-d H:i')."'";
 
}

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
    name: "Ventas Generales",
    filename: "ventas" //do not include extension
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

<form name='formlasiento' method='post' action='ventageneral.php'>
<div id='controles'>
       Horarios desde: 
         <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'> 

       Hasta: 
    <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'>

    <br/>
    <div class="filtro">
       <label for='cbimpresora' >Impresora: </label>
       <select id='cbimpresora' name='cbimpresora' onchange='cambiaimpresora(this.value);'>
        <option value=''>Todas</option>
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
    </div>
    <div class="filtro">
       <label for='cbusuario' >Cajera: </label>
       <select id='cbusuario' name='cbusuario' >
        <option value=''>Todas</option>
        <?php
        $res=ListaActivos("Usuario",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idusuario"]."'";
            if($res[$x]["idusuario"]==$lusuario)
                echo " selected ";
            echo">".$res[$x]["nombre"]."</option>";
            }
        ?>
       </select>
    </div>
    <div class="filtro">
       <label for='cbsocio' >Socio: </label>
       <select id='cbsocio' name='cbsocio' >
        <option value=''>Todos</option>
        <?php
        $res=ListaActivos("Socio",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idsocio"]."'";
            if($res[$x]["idsocio"]==$lsocio)
                echo " selected ";
            echo">".$res[$x]["Nombre"]."</option>";
            }
        ?>
       </select>
    </div>
    <div class="filtro">
       <label for='cbproducto' >Categor&iacute;a: </label>
       <select id='cbproducto' name='cbproducto' >
        <option value=''>Todos</option>
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
    </div>    
    <div class="filtro">
       <label for='cbtipoviaje' >Tipo Viaje: </label>
       <select id='cbtipoviaje' name='cbtipoviaje' >
        <option value=''>Todos</option>
        <?php
        $res=ListaActivos("TipoViaje",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idtipoviaje"]."'";
            if($res[$x]["idtipoviaje"]==$ltipoviaje)
                echo " selected ";
            echo">".$res[$x]["destipoviaje"]."</option>";
            }
        ?>
       </select>
    </div>    
    <div class="filtro">
       <label for='cbparada' >Destino: </label>
       <select id='cbparada' name='cbparada' >
        <option value=''>Todos</option>
        <?php
        $res=ListaActivos("Parada",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idparada"]."'";
            if($res[$x]["idparada"]==$lparada)
                echo " selected ";
            echo">".$res[$x]["nombre"]."</option>";
            }
        ?>
       </select>
    </div>        
    <div class="filtro">
       <label for='cbfuturo' >Sin cambiar: </label>
       <select id='cbfuturo' name='cbfuturo' >
        <option value=''>Todos</option>
        <option value='1' <?php if($lfuturo=='1') echo "selected"; ?> >Solo sin cambiar</option>
       </select>
    </div>        
    <div>

    </div>
         <br/><input type='submit' value='Volver a cargar los datos' >
         <br/> <input type='button' value='imprimir' onclick='window.print();'>
         <br/> <input type='button' value='Generar Excel' onclick='enviaexcel();'>
</div>
<div id='divdatos'>
<div id='divtitulo'>
       <h1>Consecutivo de Tiquetes </h1>
    </div>
<div id='divcuadro'>
    <?php
       $cabezatabla = "<div class='divpag'><table id='tabladatosventas' class='tabasientos'>".
       "<caption>Ventas General</caption>
       <thead>
         <tr><th colspan='3'>INGRESOS</th><th colspan='8'>DETALLE DE VENTAS</th></tr>
         <tr>
           <th>Fecha<br/>de<br/>venta</th>
           <th>Hora<br/>de<br/>Venta</th>
           <th>Monto</th>
           <th>Cajera</th>
           <th>Categor&iacute;a</th>           
           <th>Destino</th>
           <th>Tipo<br/>de<br/>Viaje</th>
           <th>Asiento</th>
           <th>Fecha<br/>del<br/>viaje</th>
           <th>Viaje</th>
           <th>Socio</th>
          </tr>
        </thead>";
       $dat= VentasGenerales(fechatoUTC($lfechai),fechatoUTC($lfechaf),$filtros) ;
        $maxpag=42; //cantidad de líneas que caben en una página
        $veces=1;
       $cont=1;
       echo $cabezatabla;
       $par ='xxxx';
       $total=0;
	   $cuenta=0;
       for($x=0;$x<count($dat);$x++){
           if($cont>=$maxpag)
           {
              $cont=1;
              if($veces>1)
                $maxpag=50; //cantidad de líneas que caben en una página
              $veces++;
         //     echo $cabezatabla;

            }
            if($dat[$x]["asiento"]>0)
              $as=$dat[$x]["asiento"];
            else
              $as="De pi&eacute;";
              
            echo "<tr><td>".fechatoNatural(substr($dat[$x]["fechadi"],0,10)).
            "</td><td>".substr($dat[$x]["fechadi"],11,10).
            "</td><td class='cmonto'>";
            if ($dat[$x]["estado"]==1){
                echo "- Nulo -";
            }else{
                echo number_format($dat[$x]["monto"],2);
            }
            echo "</td><td>".substr($dat[$x]["usuario"],0,10).
            "</td><td>".substr($dat[$x]["producto"],0,15).
            "</td><td>".substr($dat[$x]["parada"],0,15). 
            "</td><td>".substr($dat[$x]["tipoviaje"],0,15). 
            "</td><td style='text-align:center;'>".$as.
            "</td><td>".fechatoNatural(substr($dat[$x]["fechaviaje"],0,10)).
            "</td><td>".$dat[$x]["hora"].
            "</td><td>".substr($dat[$x]["socio"],0,15).
            "</td></tr>";
           $total=$total+$dat[$x]["monto"];
		   $cuenta++;
           $cont++;
       }
       echo "<tfoot><tr><th colspan='3'>Total</th><th>".number_format($cuenta,0)." boletos</th><th>".number_format($total,2)."</th><th colspan='6'></th></tr></tfoot>";
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

