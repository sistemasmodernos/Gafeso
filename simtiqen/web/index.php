<?php
  include_once('controles.php');
  inicio("sinpermiso");
  if(isset($_GET["fe"]))
    $fechac= $_GET["fe"];
 else
    $fechac=date('d/m/Y',time());
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="css/index.css" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css">  
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
       <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>  
<script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>                
   <script type="text/javascript">
        	$(document).ready(function() {
              setTimeout('tradatos1()', 5000);
              $("div#calendar").datepicker({ altField: 'input#date', altFormat: 'dd/mm/yy' , onSelect: function(dateText, inst){tradatos1();} });
               //$("#txtfecha").datepicker($.datepicker.regional['es']);

              ajusta();
	});
   function tradatos1(){
       var larut = "index.php?fe="+$("#date").val();
       traeajax('divdock',larut,null,despuestradatos1)
  }   
  function despuestradatos1(data){
               
 }
 function llegadacustom(dat){
          setTimeout('tradatos1()', 5000);
}

            function fechatoUTC(lcfecha){
                var cad = lcfecha.split('/');
                return cad[2]+"-"+cad[1]+"-"+cad[0];
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
<div id='calendario' style='float:right;'> 
   <form id='formfecha' name='formfecha' method='post' >
   <input id="date" type="textbox" class='fecha' onchange='tradatos1();' value ='<?php echo $fechac; ?>'></input>
	<div id="calendar" ></div>
    </form>
</div>

<div id='divdock'>
<?php 

$dias = array("Domingo","Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","Sabado");
$rutas = ListaActivos("Ruta",array());

$fechaini=fechatoUTC($fechac);
echo "<p><h1>Viajes programados para el ".$fechac."</h1></p>";
$fechafin = $fechaini;
$alto=100;
$ancho=65;

for($xruta=0;$xruta<count($rutas);$xruta++){
    echo "<div class='divruta' style='margin:10px;float:left;'> <h2> Ruta :".$rutas[$xruta]["nombre"]."</h2>";
    $viajes =  Viajes($rutas[$xruta]["idruta"],1,$fechaini,$fechafin);
   
    for($xviaje=0;$xviaje<count($viajes);$xviaje++){
        $factor=$alto/$viajes[$xviaje]["cantpas"];
        $avance=round($factor*$viajes[$xviaje]["vendidos"],0);
        $falta = $alto-$avance;
        if($viajes[$xviaje]["extra"]==1)
          $color='blue';
        else
          $color='black';
        echo "<div class='divviaje'>";
        echo "<div style='margin-bottom:5px;text-align:center;'>";
//        echo $dias[date('w',strtotime($viajes[$xviaje]["fecha"]))]. "<br/> ";
//        echo  date('d/m/Y',strtotime($viajes[$xviaje]["fecha"]) );
        echo "<br/> <span > " . $viajes[$xviaje]["hora"] . "</span><br/>";
        echo " </div>";
        echo " <div class='divinterno' >";
        echo " <div class='placa'>". $viajes[$xviaje]["placa"]."</div>";
        echo "    <div class='grafico' style='height:".$alto."px;float:right;width:".$ancho."px;border-style:solid;border-width:1px;border-color:".$color.";'>";
        echo "    <div style='float:left;margin-top:1px;heigth:10px'>".$viajes[$xviaje]["cantpas"]."</div>";
        echo "    <div style='float:right;margin-top:".$falta."px;heigth:10px'>".$viajes[$xviaje]["vendidos"]."</div>";
        
        echo "        <div style='width:".$ancho."px;background-color:#d34808;margin-top:".$falta."px;height:".$avance."px;'></div>";
         echo "    </div>";
        echo  " </div>";
        if(ObjetoValido("verdepie"))
           echo " <p> &nbsp; De pie: ".$viajes[$xviaje]["depie"]."</p>";
        echo  "</div>";        
    }
    echo "</div>";
}
?>
</div>
</div>
</div>
<?php
  Pie();
?>
</body>
</html>
