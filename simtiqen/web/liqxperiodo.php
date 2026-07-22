<?php
  include_once('controles.php');
  inicio("liqxperiodo");
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
$lusuario="";
$lsocio="";
$lproducto="";
$ltipoviaje="";


if(isset($_POST["cbruta"]) && $_POST["cbruta"]!=""){
  $filtros.=" and r.idruta= ".$_POST["cbruta"];
  $lruta =$_POST["cbruta"];
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

if(isset($_POST["cbbus"]) && $_POST["cbbus"]!=""){
  $filtros.=" and b.idbus= ".$_POST["cbbus"];    
  $ltipoviaje=$_POST["cbbus"];
}

$ltitulo="";

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
        <script type="text/javascript" src="js/jquery.timeentry-es.js"></script>
        <script type="text/javascript" src="js/jquery.timeentry.min.js"></script>
        <script type="text/javascript" src="js/jquery.timeentry.js"></script>        
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

<form name='formlasiento' method='post' action='liqxperiodo.php'>
<div id='controles'>
       Horarios desde: 
        <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'> 

       Hasta: 
        <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'>

    <br/>
    <div class="filtro">
       <label for='cbruta' >Ruta: </label>
       <select id='cbruta' name='cbruta' >
        <option value=''>Todas</option>
        <?php
        $res=ListaActivos("Ruta",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idruta"]."'";
            if($res[$x]["idruta"]==$lruta){
                echo " selected ";
                $ltitulo.=" de la ruta ".$res[$x]["nombre"]."<br/>";
            }
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
            if($res[$x]["idusuario"]==$lusuario){
                echo " selected ";
                $ltitulo.=" del usuario ".trim($res[$x]["nombre"])."<br/>";
            }
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
            if($res[$x]["idsocio"]==$lsocio){
                echo " selected ";
                $ltitulo.=" del socio ".trim($res[$x]["Nombre"])."<br/>";
            }
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
            if($res[$x]["idProducto"]==$lproducto){
                echo " selected ";
                $ltitulo.=" de la categor&iacute;a ".trim($res[$x]["nombre"])."<br/>";
            }
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
            if($res[$x]["idtipoviaje"]==$ltipoviaje){
                echo " selected ";
                $ltitulo.=" del tipo de viaje ".trim($res[$x]["destipoviaje"])."<br/>";
            }
            echo">".$res[$x]["destipoviaje"]."</option>";
            }
        ?>
       </select>
    </div>    
<div class="filtro">
       <label for='cbbus' >Bus: </label>
       <select id='cbbus' name='cbbus' >
        <option value=''>Todos</option>
        <?php
        $res=ListaActivos("Bus",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idbus"]."'";
            if($res[$x]["idbus"]==$ltipoviaje){
                echo " selected ";
                $ltitulo.=" del Bus ".trim($res[$x]["placa"])."<br/>";
            }
            echo">".$res[$x]["placa"]."</option>";
            }
        ?>
       </select>
    </div>    
    <div>

    </div>
         <br/><input type='submit' value='Volver a cargar los datos' >
         <br/> <input type='button' value='imprimir' onclick='window.print();'>
</div>
<div id='divdatos'>
<div id='divtitulo'>
      <h1> Liquidaci&oacute;n por periodo </h1>
      <h2><?php echo $ltitulo; ?></h2>

    </div>
<div id='divcuadro'>
    <?php
       $cabezatabla = "<div class='divpag'><table class='tabasientos'>
       <thead>
         <tr>
           <th>Estaci&oacute;n</th>
           <th>Fecha</th>
           <th>Hora</th>
           <th>Placa</th>
           <th>Socio</th>
           <th>Cantidad</th>
           <th>Monto</th>
           <th>Rebajo</th>           
           <th>Neto</th>           
          </tr>
        </thead>";
       $dat= LiquidacionxPeriodo(fechatoUTC($lfechai),fechatoUTC($lfechaf),$filtros) ;
       
       echo $cabezatabla;
       $par ='xxxx';
       $total=0;
       $totalr=0;
       for($x=0;$x<count($dat);$x++){
            echo "<tr>" .
            "     <td>".$dat[$x]["estacion"].
            "</td><td>".fechatoNatural(substr($dat[$x]["fecha"],0,10)).
            "</td><td>".$dat[$x]["horaini"].
            "</td><td>".$dat[$x]["placa"].
            "</td><td>".$dat[$x]["socio"].
            "</td><td style='text-align:center;'>".$dat[$x]["cantidad"].
            "</td><td class='cmonto'>".number_format($dat[$x]["monto"],2).
            "</td><td class='cmonto'>".number_format($dat[$x]["rebajo"],2).
            "</td><td class='cmonto'>".number_format($dat[$x]["monto"]-$dat[$x]["rebajo"],2).
            "</td></tr>";
           $total=$total+$dat[$x]["monto"];
           $totalr=$totalr+$dat[$x]["rebajo"];
           
       }
       echo "<tfoot>
         <tr>
           <th colspan='6'>Total</th>
           <th>".number_format($total,2)."</th>
           <th>".number_format($totalr,2)."</th>
           <th>".number_format($total-$totalr,2)."</th>
          </tr>
        </tfoot>";
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

