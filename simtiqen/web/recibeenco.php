<?php
  include_once('controles.php');
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  inicio("recibeenco");
   
  if(isset($_POST["cbestacion"]))
    $lestacion=$_POST["cbestacion"];
 else
    $lestacion=$_SESSION["parametros"]["estaciondefault"];

  if(isset($_POST["cbparadades"]))
    $lparadades=$_POST["cbparadades"];
 else
    $lparadades=$_SESSION["parametros"]["paradaenco"];

 if(isset($_POST["cbruta"]))
    $lruta=$_POST["cbruta"];
 else
    $lruta=$_SESSION["parametros"]["rutadefault"];

    
 if(isset($_POST["txtfechai" ]))
    $lfechai=$_POST["txtfechai" ];
 else
    $lfechai=date("d/m/Y",time());

if(isset($_POST["txtfechaf" ]))
    $lfechaf=$_POST["txtfechaf" ];
 else
    $lfechaf=date("d/m/Y",time());
    
if(isset($_POST["cbviaje"]) )   
  $lviaje=$_POST["cbviaje"];
else{
   $dd= DatosDefault($_SESSION["parametros"]["rutadefault"],$lestacion);
  $lviaje=$dd["idviaje"];
  }

if(isset($_POST["numero" ]))
{
	RecibeEncomienda($_POST["numero"]);
}  
  ?>
  <html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
	<title>Sistemas de ventas de tiquetes</title> 
	<link rel="stylesheet" href="css/general.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="stylesheet" href="css/remision.css" type="text/css" />
 	<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="js/sfunciones.js"></script>
	<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>        
    <script type="text/javascript">
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
		
		function marcatodo(){ 
			$("#divenco input[type=checkbox]").attr('checked', 'checked');
		}

		function desmarcatodo(){ 
			$("#divenco input[type=checkbox]").removeAttr('checked');
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
<h1> Recibir las encomiendas en Estaci&oacute;n </h1>
<form id='formremesa' method='post' action='recibeenco.php'>
    <div id='controles'>
      Estaci&oacute;n emisi&oacute;n <select id='cbestacion' name='cbestacion' onchange='cambiaestacion(this.value);'>
    <?php
    $res=ListaActivos("Estacion",null);
    for($x=0;$x<count($res);$x++){
        echo "<option value='". $res[$x]["idestacion"]."'";
        if($res[$x]["idestacion"]==$lestacion)
            echo " selected ";
        echo">".$res[$x]["nombre"]."</option>";
        }
    ?>
  </select>
    <br/>
    Parada destino  <select id='cbparadades' name='cbparadades' >
                <?php
                    $res=ListaActivos("Parada",null);
                    for($x=0;$x<count($res);$x++){
                    echo "<option value='". $res[$x]["idparada"]."'";
                    if($res[$x]["idparada"]==$lparadades)
                      echo "selected";
                    echo">".$res[$x]["nombre"]."</option>";
                    }
                ?>
                </select>
    <br/>
       Horarios desde: <input type='text' name='txtfechai' id='txtfechai' value='<?php echo $lfechai; ?>'> 
       Hasta: <input type='text' name='txtfechaf' id='txtfechaf' value='<?php echo $lfechaf; ?>'>
       <br/>
       <select id='cbviaje' name='cbviaje' >
        <?php
			$res=ListaActivos("Viaje",array("fecha between '". fechatoUTC($lfechai)."' and '". fechatoUTC($lfechaf)."'","idruta=".$lruta));
			for($x=0;$x<count($res);$x++){
				echo "<option value='". $res[$x]["idviaje"]."'";
				if($res[$x]["idviaje"]==$lviaje)
					echo " selected ";
				echo">".substr($res[$x]["fecha"],0,10)." ".$res[$x]["hora"]."</option>";
            }
        ?>
        </select>
        <br/><input type='submit' value='Volver a cargar los datos' >
    </div>
    <div id='divdatos'>
    <div id='divtitulo'>
       <div id='infoviaje'>
      <?php
           $res = Remision($lviaje,$lestacion,$lparadades);
           if(count($res)>0){
           echo "Bus: ".$res[0]["placa"]."<br/>";
           echo "Chofer: ".$res[0]["chofer"]."<br/>";
           echo "Cobrador: ".$res[0]["cobrador"]."<br/>";
           echo "Fecha de salida " . fechatoNatural(substr($res[0]["fecha"],0,10)) . " ".$res[0]["hora"];
           }
        ?>          
       </div>
	   <p>&nbsp;<p/>
	   <p>&nbsp;<p/>
    </div>
      <?php
			if(count($res)>0){
				//echo "<a href='javascript:marcatodo()'>Marcar todos</a> | <a href='javascript:desmarcatodo()'>Desmarcar todos</a> ";
				echo "<input type='submit' value='Marcar como recibidas las encomiendas' >";
				for($x=0;$x<count($res);$x++)
				{
					echo "<br/><div class='divenco'><p><hr></p>";
					echo "<div style='float:left;width:150px;margin:5px;'>";
					if ($res[$x]["idtipotrack"] > 4){
						echo " Ya recibida <br>";
					}else{
						echo " <input type='checkbox' name='numero[]' value='" . $res[$x]["idencomienda"] . "' checked/> ";
					}
					echo " <span class='numenco'>" . $res[$x]["factura"] . "</span><br/>";
					echo " Bultos: ".$res[$x]["cantbul"]."<br/>";
					echo $res[$x]["nota"] ."<br/>";
					echo $res[$x]["fechadi"]."</div>";
					echo "<div style='float:left;border-style-left:solid;width:170px;margin:5px;' > ";
					echo $res[$x]["remitente"]."<br/>".$res[$x]["cedremi"]."<br/><br/>";
					echo $res[$x]["destinatario"]."<br/>".$res[$x]["cedremi"]."</div>";
					echo "<div style='float:left;margin:5px;padding-left:5px;'>";
					echo  $res[$x]["usuario"]. "<br\><br/>";
					echo "<span style='font-weigth:bold;'>Destino:</span> <br/>".$res[$x]["parada"];
					echo "</div></div>";
				}
			}
            else
              echo "No existen encomiendas para este horario";
      ?>
    </div>
    </form>
</div>
</div>
<?php
  Pie();
?>
</body>
</html>  
