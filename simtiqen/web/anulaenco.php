<?php
  include_once('controles.php');
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  inicio("proanulaenc");
  $res="";
  if(isset($_POST["ckmarca"]) && isset($_GET["do"]) && $_GET["do"]=="anular")
  {
      $marcados=$_POST["ckmarca"];
      try{
      for($x=0;$x<count($marcados);$x++){
          $res.="Anulando ...". $x ."<br/>";
          AnularEncomienda($marcados[$x],$_POST["txtanula"]);
      }
      
      $algo =count($marcados);
      $res.= " ".$algo." Encomiendas anuladas correctamente ";
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
    
 ?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
        <link rel="stylesheet" href="css/general.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />        
        <link type="text/css" href="css/anulaenco.css" rel="stylesheet" />       
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />        
        <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/anulaenco.js"></script>
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

<form id='fanulaenco' name='fanulaenco' action='anulaenco.php' method='post'>
    <div id='divanular'>
     <p class='mensaje' <?php if(trim($res)=="") echo "style='display:none;' "; ?> ><?php echo $res; ?></p>
     
      Raz&oacute;n por la cual se anular&aacute;n las encomiendas: <br/>
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
          <th>Viaje</th>
          <th>Encomienda</th>
          <th>Remitente</th>
          <th>Monto</th>
       </tr>
       </thead>
       <tbody>
       <?php 
         $filtros = array();
         array_push($filtros,sprintf("e.fecha between '%s' and '%s' ",$fechai,$fechaf));
         array_push($filtros,"e.idestacion=".$estacion);
         array_push($filtros,"e.estado=0");
          $lista = ListaActivos("Encomienda",$filtros);
         for($x=0;$x<count($lista);$x++){
             echo "<tr>".
             "<td><input type='checkbox' name='ckmarca[]' value='".$lista[$x]["idencomienda"]."'> </td>".
             "<td>".fechatoNatural(substr($lista[$x]["fechaviaje"],0,10))." ".$lista[$x]["hora"]."</td>".
             "<td>".$lista[$x]["factura"]."</td>".
             "<td>".$lista[$x]["remitente"]."</td>".
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
