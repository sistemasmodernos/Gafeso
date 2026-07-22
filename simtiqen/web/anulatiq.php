<?php
  include_once('controles.php');
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  inicio("proanulatiq");
  $res="";
  if(isset($_POST["ckmarca"]) && isset($_GET["do"]) && $_GET["do"]=="anular")
  {
      $marcados=$_POST["ckmarca"];
      try{
      for($x=0;$x<count($marcados);$x++){
          $res.="Anulando ...". $x ."<br/>";
          AnularTiquete($marcados[$x],$_POST["txtanula"]);
      }
      
      $algo =count($marcados);
      $res.= " ".$algo." tiquetes anulados correctamente ";
     }
     catch(simError $ex){
         $res.=$ex->getMessage();
    }
  }
  if(isset($_POST["txtfechai"])){
    $fechai = fechatoUTC($_POST["txtfechai"]);
    }
  else{
    $fechai=date('Y-m-d',time());
    }
    
  if(isset($_POST["txtfechaf"]))
    $fechaf = fechatoUTC($_POST["txtfechaf"]);
 else
    $fechaf = $fechai;
    
if(isset($_POST["cbestacion"])){
    $estacion=$_POST["cbestacion"];
    }
 else
   $estacion= $_SESSION["parametros"]["estaciondefault"];

 if(isset($_POST["cbimpresora"]))
    $limpresora=$_POST["cbimpresora"];
  else
    $limpresora=$_SESSION["parametros"]["impresoradefault"];
   

 ?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
        <link rel="stylesheet" href="css/general.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />        
        <link type="text/css" href="css/anulatiq.css" rel="stylesheet" />       
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />        
        <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/anulatiq.js"></script>
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

<form id='fanulatiq' name='fanulatiq' action='anulatiq.php' method='post'>
    <div id='divanular'>
     <p class='mensaje' <?php if(trim($res)=="") echo "style='display:none;' "; ?> ><?php echo $res; ?></p>
     
      Raz&oacute;n por la cual se anular&aacute;n los tiquetes: <br/>
      <input type='text' name='txtanula' id='txtanula'><br/>
       <input type='submit' value='Anular' onclick='anulartiq();'>
    </div>
    <select id='cbestacion' name='cbestacion'  onchange='cargar();'>
    <?php
    $res=ListaActivos("Estacion",null);
    for($x=0;$x<count($res);$x++){
        echo "<option value='". $res[$x]["idestacion"]."'";
        if($res[$x]["idestacion"]==$estacion)
            echo " selected ";
        echo">".$res[$x]["nombre"]."</option>";
        }
    ?>
  </select>
  <div>
  <div id='buscatiq' style='border-style:solid;border-width:1px;padding:5px;margin:5px;display:inline-block;'>
  <select id='cbimpresora' name='cbimpresora' >
    <?php
    $res=ListaActivos("Impresora",null);
    for($x=0;$x<count($res);$x++){
        echo "<option value='". $res[$x]["idimpresora"]."' ";
        if($res[$x]["idimpresora"]==$limpresora)
                echo " selected ";
        echo">".$res[$x]["nombre"]."</option>";
        }
    ?>
  </select>
     Este tiquete:<input type='text' name='txteltiq' id='txteltiq'><input type='button' onclick='traetiq();' value='Cargar'>
     <div id='divtiq'>
     </div>
  </div>
  </div>
    <table>
      <tr><td>Desde</td><td>Hasta</td></tr>
      <tr>
        <td><input type='text' class='fecha' name='txtfechai' id='txtfechai' onchange='cargar();' value='<?php echo fechatoNatural($fechai); ?>'></td>
        <td><input type='text' class='fecha' name='txtfechaf' id='txtfechaf' onchange='cargar();' value='<?php echo fechatoNatural($fechaf); ?>'><td>
      </tr>
      <tr><td colspan='2'><input type='submit' value='volver a cargar' ></td></tr>
    </table>
    <br/>
    <table id='tablatiq' border='1px'>
       <thead>
       <tr>
          <th>Anular?</th>
            <th>Ruta</th>          
          <th>Viaje</th>
          <th>Tiquete</th>
          <th>Asiento</th>
          <th>Monto</th>
       </tr>
       </thead>
       <tbody>
       <?php 
         $filtros = array();
         array_push($filtros,sprintf("t.fecha between '%s' and '%s' ",$fechai,$fechaf));
         array_push($filtros,"t.idestacion=".$estacion);
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
