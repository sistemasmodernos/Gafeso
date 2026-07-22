<?php
  include_once('controles.php');
  inicio("repconsetiq");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
 if(isset($_POST["txtfechai" ]))
    $lfechai=$_POST["txtfechai" ];
 else
    $lfechai=date("d/m/Y",time());
    
if(isset($_POST["txthorai"]))
    $lhorai=$_POST["txthorai"];
else
   $lhorai='00:00';

if(isset($_POST["txthoraf"]))
    $lhoraf=$_POST["txthoraf"];
else
   $lhoraf='23:59';

if(isset($_POST["txtidcierre"]))
   $lidcierre=$_POST["txtidcierre"];
 else
   $lidcierre=0;

if(isset($_POST["ckcierre"]) )   {
  $ckcierre=$_POST["ckcierre"];
}
else{
 $ckcierre="1";
}


if(isset($_POST["txtfechaf" ]))
    $lfechaf=$_POST["txtfechaf" ];
 else
    $lfechaf=date("d/m/Y",time());

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
  <link rel="stylesheet" href="css/lconsetiq.css" type="text/css" />
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
        $("#txthorai").timeEntry({show24Hours: true});
        $("#txthoraf").timeEntry({show24Hours: true});        
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

<form name='formlasiento' method='post' action='lconsetiq.php'>
<div id='controles'>
       Horarios desde: 
         <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'> 
         <input type='hidden' name='txthorai' id='txthorai' value='<?php echo $lhorai; ?>' style='width:50px;'>
       Hasta: 
       <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'>
       <input type='hidden' name='txthoraf' id='txthoraf' value='<?php echo $lhoraf; ?>' style='width:50px;'>
       <br/>
       Impresora: <select id='cbimpresora' name='cbimpresora' onchange='cambiaimpresora(this.value);'>
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
    <br/>

    <span>n&uacute;mero de Cierre:</span>
      <input type='text' name='txtidcierre' id="txtidcierre" value='<?php echo $lidcierre; ?>' >
      <br/>    
     <span >Listar: </span>
      <input type="radio" name="ckcierre" id="ckcierre" value="1" <?php if ($ckcierre=="1") echo "checked"; ?> >Todos los tiquetes
      <input type="radio" name="ckcierre" id="ckcierre" value="2" <?php if ($ckcierre=="2") echo "checked"; ?> >Solo tiquetes en Cierres
      <input type="radio" name="ckcierre" id="ckcierre" value="3" <?php if ($ckcierre=="3") echo "checked"; ?> >Solo Tiquetes sin Cerrar
         <br/><input type='submit' value='Volver a cargar los datos' >
         <br/> <input type='button' value='imprimir' onclick='window.print();'>
</div>
<div id='divdatos'>
<div id='divtitulo'>
      <p> Consecutivo de Tiquetes <br/> Impresora 
      <?php 
       $dat=ListaActivos("Impresora",array("idimpresora=".$limpresora));
       echo $dat[0]["nombre"];
      ?></p>
    </div>
<div id='divcuadro'>
    <?php
      if ($lidcierre == 0){
        if ($ckcierre=="1"){
             $dat= ListaActivos("Tiquete",array(
                    "t.fechadi between '".fechatoUTC($lfechai). ' '.$lhorai."' and '".fechatoUTC($lfechaf). ' '.$lhoraf."' ",
                    "t.idimpresora=".$limpresora
                    )) ;
        }else{
            if ($ckcierre=="2"){
               $dat= ListaActivos("Tiquete",array(
                      "t.fechadi between '".fechatoUTC($lfechai). ' '.$lhorai."' and '".fechatoUTC($lfechaf). ' '.$lhoraf."' ",
                      "t.idimpresora=".$limpresora,
                      "t.idcierre != 0"
                    )) ;
            }else{
               $dat= ListaActivos("Tiquete",array(
                      "t.fechadi between '".fechatoUTC($lfechai). ' '.$lhorai."' and '".fechatoUTC($lfechaf). ' '.$lhoraf."' ",
                      "t.idimpresora=".$limpresora,
                      "t.idcierre = 0"
                    )) ;
          }
          }
      }else{
          $dat= ListaActivos("Tiquete",array(
                  "t.idcierre=".$lidcierre,
                  "t.idimpresora=".$limpresora
                  )) ;
      }
      $dat = ordenaMatriz($dat,"factura");
      $maxpag=42; //cantidad de líneas que caben en una página
      $veces=1;
       $cont=1;
       echo "<div class='divpag'><table class='tabasientos'>".
       "<thead><tr><th>Tiquete</th><th>F.viaje</th><th>Hora</th><th>Cliente</th><th>Usu</th><th>Asiento</th><th>Monto</th><th>Cierre</th><th>Digitado</th></tr></thead>";
       $par ='xxxx';
       $total=0;
       for($x=0;$x<count($dat);$x++){
           if($cont>=$maxpag)
           {
               $cont=1;
               if($veces>1)
                  $maxpag=50; //cantidad de líneas que caben en una página
               $veces++;
       echo "</table></div><div class='divpag'><table class='tabasientos'>".
       "<thead><tr><th>Tiquete</th><th>F.viaje</th><th>Hora</th><th>Cliente</th><th>Usu</th><th>Asiento</th><th>Monto</th><th>Cierre</th><th>Digitado</th></tr></thead>";

            }
            if($dat[$x]["asiento"]>0)
             $as=$dat[$x]["asiento"];
            else
              $as="De pi&eacute;";
              
            echo "<tr><td>".$dat[$x]["factura"].
            "</td><td>".fechatoNatural(substr($dat[$x]["fechaviaje"],0,10)).
            "</td><td>".$dat[$x]["hora"].
           "</td><td>".substr($dat[$x]["cliente"],0,10).
           "</td><td>".substr($dat[$x]["usuario"],0,3).
           "</td><td>".$as;
           if($dat[$x]["estado"]==0){
              echo "</td><td class='cmonto'>".number_format($dat[$x]["monto"],2);
              $total+=$dat[$x]["monto"];
           }
           else
             echo "</td><td >Anulado";
          if ($dat[$x]["idcierre"] != 0){
            echo "</td><td>".$dat[$x]["idcierre"];
          }else{
            echo "</td><td>Sin Cierre";
          }
           echo "</td><td>".fechatoNatural(substr($dat[$x]["fechadi"],0,10))." ".date("H:i:s", strtotime($dat[$x]["fechadi"]));
           echo "</td></tr>";
           $cont++;
       }
       echo "<tfoot><tr><th colspan='6'>Total</th><th>".number_format($total,2)."</th></tr></tfoot>";
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

