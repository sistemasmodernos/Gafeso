<?php
  include_once('controles.php');
  inicio("proreimtiq");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
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
    		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
        <script type="text/javascript" src="js/reimtiq.js"></script>
  		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
          <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
            $(function(){
              //  $('#txtnumero').numeric({minValue:0,increment:1, format:"#000000" ,emptyValue: 0});
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
 <div style='float:right;margin:20px;border-style:solid;border-width:1px;padding:10px;background-color:gray;'>
   Vista preliminar del tiquete<br/>
   <textarea id='textotiq' rows='30' cols='45'>
   </textarea>
   
 </div>
Impresora: 
<select id='cbimpresora' name='cbimpresora' >
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
    <form>
       <label><input type="radio" name="tiqtip" value="T" checked>Tiquete</label> 
        <label><input type="radio" name="tiqtip" value="E">Encomienda</label>
	</form>
    <br/>
N&uacute;mero de tiquete a reimprimir:
 <input type='number' min='0'  id='txtnumero' name='txtnumero'>
 <br/>
 <input type='button' value='preliminar' onclick='preliminar();'><br/>
 <input type='button' value='imprimir' onclick='imprimir();'>
</div>
</div>
<?php
  Pie();
   
?>


</body>
</html>
